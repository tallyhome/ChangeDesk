<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\BugReport;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Version;
use App\Models\Visit;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $mrr = Subscription::query()
            ->whereIn('status', ['active', 'trialing'])
            ->join('plans', 'plans.id', '=', 'subscriptions.plan_id')
            ->sum('plans.price_cents');

        $stats = [
            'tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('is_active', true)->whereNull('suspended_at')->count(),
            'users' => User::where('role', User::ROLE_CLIENT)->count(),
            'versions' => Version::withoutGlobalScope('tenant')->count(),
            'bugs' => BugReport::withoutGlobalScope('tenant')->count(),
            'visits' => Visit::withoutGlobalScope('tenant')->count(),
            'subscriptions' => Subscription::whereIn('status', ['active', 'trialing'])->count(),
            'mrr' => $mrr,
            'payments_paid' => Payment::where('status', 'paid')->sum('amount_cents'),
        ];

        $recentTenants = Tenant::latest()->take(8)->get();
        $recentPayments = Payment::with(['tenant', 'plan'])->latest()->take(8)->get();

        return view('superadmin.dashboard', compact('stats', 'recentTenants', 'recentPayments'));
    }
}
