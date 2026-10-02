<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Services\Billing\StripeBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request, StripeBilling $stripe)
    {
        $raw = $request->getContent();

        try {
            $stripe->assertValidWebhook($raw, $request->header('Stripe-Signature'));
            $payload = json_decode($raw, true);
            if (! is_array($payload)) {
                return response('invalid', 400);
            }

            Log::info('stripe.webhook', ['type' => $payload['type'] ?? null]);
            $stripe->handleWebhook($payload);
        } catch (\Throwable $e) {
            Log::error('stripe.webhook.error', ['message' => $e->getMessage()]);

            return response('error', 400);
        }

        return response('ok', 200);
    }
}
