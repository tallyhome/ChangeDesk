<?php

namespace App\Services\Billing;

use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PayPalBilling
{
    protected function baseUrl(): string
    {
        return config('billing.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    public function accessToken(): string
    {
        return Cache::remember('paypal_access_token', 300, function () {
            $response = Http::asForm()
                ->withBasicAuth(config('billing.paypal.client_id'), config('billing.paypal.client_secret'))
                ->post($this->baseUrl().'/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if (! $response->successful()) {
                throw new \RuntimeException('PayPal auth failed: '.$response->body());
            }

            return $response->json('access_token');
        });
    }

    public function createSubscription(Tenant $tenant, Plan $plan, string $returnUrl, string $cancelUrl): string
    {
        $paypalPlanId = $plan->paypal_plan_id ?: config('billing.paypal.plans.'.$plan->slug);
        if (! $paypalPlanId || ! config('billing.paypal.client_id')) {
            throw new \RuntimeException('PayPal n\'est pas configuré pour ce plan.');
        }

        $response = Http::withToken($this->accessToken())
            ->post($this->baseUrl().'/v1/billing/subscriptions', [
                'plan_id' => $paypalPlanId,
                'custom_id' => json_encode(['tenant_id' => $tenant->id, 'plan_id' => $plan->id]),
                'application_context' => [
                    'brand_name' => config('app.name', 'Evolora'),
                    'user_action' => 'SUBSCRIBE_NOW',
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                ],
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('PayPal subscription erreur: '.$response->body());
        }

        $approve = collect($response->json('links') ?? [])->firstWhere('rel', 'approve');
        $subId = $response->json('id');

        Payment::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'provider' => 'paypal',
            'provider_payment_id' => $subId,
            'amount_cents' => $plan->price_cents,
            'currency' => config('billing.currency', 'eur'),
            'status' => 'pending',
            'metadata' => $response->json(),
        ]);

        if (! $approve || empty($approve['href'])) {
            throw new \RuntimeException('Lien PayPal approve introuvable.');
        }

        return $approve['href'];
    }

    public function handleWebhook(array $payload): void
    {
        $event = $payload['event_type'] ?? '';
        $resource = $payload['resource'] ?? [];

        if (in_array($event, ['BILLING.SUBSCRIPTION.ACTIVATED', 'CHECKOUT.ORDER.APPROVED', 'PAYMENT.SALE.COMPLETED'], true)) {
            $custom = json_decode($resource['custom_id'] ?? $resource['custom'] ?? '{}', true) ?: [];
            $tenant = Tenant::find((int) ($custom['tenant_id'] ?? 0));
            $plan = Plan::find((int) ($custom['plan_id'] ?? 0));
            if (! $tenant || ! $plan) {
                // Fallback: find pending payment by subscription id
                $payment = Payment::where('provider', 'paypal')
                    ->where('provider_payment_id', $resource['id'] ?? null)
                    ->first();
                if ($payment) {
                    $tenant = $payment->tenant;
                    $plan = $payment->plan;
                }
            }

            if ($tenant && $plan) {
                app(StripeBilling::class)->activate(
                    $tenant,
                    $plan,
                    'paypal',
                    $resource['id'] ?? Str::uuid()->toString()
                );
                Payment::where('provider_payment_id', $resource['id'] ?? null)->update(['status' => 'paid']);
                AuditLog::record('billing.paypal.activated', $tenant, ['event' => $event]);
            }
        }

        if ($event === 'BILLING.SUBSCRIPTION.CANCELLED') {
            $sub = \App\Models\Subscription::where('provider', 'paypal')
                ->where('provider_subscription_id', $resource['id'] ?? null)
                ->first();
            if ($sub) {
                $sub->update(['status' => 'canceled', 'canceled_at' => now()]);
                $free = Plan::where('slug', 'free')->first();
                if ($free) {
                    $sub->tenant->update(['plan_id' => $free->id]);
                }
            }
        }
    }
}
