<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Services\Billing\StripeBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BillingController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['tenant', 'plan'])->latest()->paginate(20, ['*'], 'subs');
        $payments = Payment::with(['tenant', 'plan'])->latest()->paginate(20, ['*'], 'pays');
        $plans = Plan::orderBy('sort_order')->get();

        return view('superadmin.billing.index', compact('subscriptions', 'payments', 'plans'));
    }

    public function assign(Request $request)
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'exists:tenants,id'],
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $tenant = Tenant::findOrFail($data['tenant_id']);
        $plan = Plan::findOrFail($data['plan_id']);

        app(StripeBilling::class)->activate($tenant, $plan, 'manual', 'manual-'.Str::uuid());
        AuditLog::record('billing.manual_assign', $tenant, $data);

        return back()->with('success', __('app.flash.plan_assigned', ['plan' => $plan->name, 'tenant' => $tenant->name]));
    }
}
