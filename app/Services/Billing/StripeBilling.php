<?php

namespace App\Services\Billing;

use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class StripeBilling
{
    public function createCheckoutSession(Tenant $tenant, Plan $plan, string $successUrl, string $cancelUrl): string
    {
        $priceId = $plan->stripe_price_id ?: config('billing.stripe.prices.'.$plan->slug);
        if (! $priceId || ! config('billing.stripe.secret')) {
            throw new \RuntimeException('Stripe n\'est pas configuré pour ce plan.');
        }

        $response = Http::withToken(config('billing.stripe.secret'))
            ->asForm()
            ->post('https://api.stripe.com/v1/checkout/sessions', [
                'mode' => 'subscription',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'client_reference_id' => (string) $tenant->id,
                'metadata[tenant_id]' => (string) $tenant->id,
                'metadata[plan_id]' => (string) $plan->id,
                'line_items[0][price]' => $priceId,
                'line_items[0][quantity]' => 1,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Stripe Checkout erreur: '.$response->body());
        }

        Payment::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'provider' => 'stripe',
            'provider_payment_id' => $response->json('id'),
            'amount_cents' => $plan->price_cents,
            'currency' => config('billing.currency', 'eur'),
            'status' => 'pending',
            'metadata' => $response->json(),
        ]);

        return $response->json('url');
    }

    public function handleWebhook(array $payload): void
    {
        $type = $payload['type'] ?? '';
        $object = $payload['data']['object'] ?? [];

        if ($type === 'checkout.session.completed') {
            $tenantId = (int) ($object['metadata']['tenant_id'] ?? $object['client_reference_id'] ?? 0);
            $planId = (int) ($object['metadata']['plan_id'] ?? 0);
            $tenant = Tenant::find($tenantId);
            $plan = Plan::find($planId);
            if (! $tenant || ! $plan) {
                return;
            }

            $this->activate($tenant, $plan, 'stripe', $object['subscription'] ?? $object['id'] ?? Str::uuid()->toString(), $object['customer'] ?? null);

            Payment::where('provider_payment_id', $object['id'] ?? null)->update(['status' => 'paid']);
            AuditLog::record('billing.stripe.checkout_completed', $tenant, ['session' => $object['id'] ?? null]);
        }

        if (in_array($type, ['customer.subscription.deleted', 'customer.subscription.updated'], true)) {
            $subId = $object['id'] ?? null;
            $subscription = Subscription::where('provider', 'stripe')
                ->where('provider_subscription_id', $subId)
                ->first();
            if (! $subscription) {
                return;
            }

            $status = $object['status'] ?? 'canceled';
            $subscription->update([
                'status' => $status === 'active' ? 'active' : ($status === 'trialing' ? 'trialing' : 'canceled'),
                'canceled_at' => $status === 'canceled' ? now() : null,
            ]);

            if ($subscription->status === 'canceled') {
                $free = Plan::where('slug', 'free')->first();
                if ($free) {
                    $subscription->tenant->update(['plan_id' => $free->id]);
                }
            }
        }
    }

    public function activate(Tenant $tenant, Plan $plan, string $provider, string $providerSubId, ?string $customerId = null): Subscription
    {
        Subscription::where('tenant_id', $tenant->id)
            ->whereIn('status', ['active', 'trialing'])
            ->update(['status' => 'canceled', 'canceled_at' => now()]);

        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'provider' => $provider,
            'provider_subscription_id' => $providerSubId,
            'provider_customer_id' => $customerId,
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
        ]);

        $tenant->update(['plan_id' => $plan->id]);

        return $subscription;
    }
}
