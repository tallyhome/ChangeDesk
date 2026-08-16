<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Tenant;
use App\Services\Billing\PayPalBilling;
use App\Services\Billing\StripeBilling;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        $tenant = Tenant::current()->load(['plan', 'subscriptions.plan']);
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $payments = Payment::where('tenant_id', $tenant->id)->latest()->take(20)->get();

        return view('admin.billing.index', compact('tenant', 'plans', 'payments'));
    }

    public function checkout(Request $request, StripeBilling $stripe, PayPalBilling $paypal)
    {
        $tenant = Tenant::current();
        $data = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
            'provider' => ['required', 'in:stripe,paypal'],
        ]);

        $plan = Plan::findOrFail($data['plan_id']);
        abort_if($plan->slug === 'free', 422, 'Le plan Free ne nécessite pas de paiement.');

        $success = route('admin.billing.success');
        $cancel = route('admin.billing.index');

        try {
            $url = $data['provider'] === 'stripe'
                ? $stripe->createCheckoutSession($tenant, $plan, $success.'?session_id={CHECKOUT_SESSION_ID}', $cancel)
                : $paypal->createSubscription($tenant, $plan, $success, $cancel);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->away($url);
    }

    public function success()
    {
        return redirect()->route('admin.billing.index')
            ->with('success', __('app.flash.billing_pending'));
    }
}
