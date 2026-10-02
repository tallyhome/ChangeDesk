<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Services\Billing\PayPalBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalWebhookController extends Controller
{
    public function __invoke(Request $request, PayPalBilling $paypal)
    {
        try {
            $paypal->assertValidWebhook($request);
            $payload = json_decode($request->getContent(), true);
            if (! is_array($payload)) {
                return response('invalid', 400);
            }

            Log::info('paypal.webhook', ['type' => $payload['event_type'] ?? null]);
            $paypal->handleWebhook($payload);
        } catch (\Throwable $e) {
            Log::error('paypal.webhook.error', ['message' => $e->getMessage()]);

            return response('error', 400);
        }

        return response('ok', 200);
    }
}
