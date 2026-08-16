<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Tenant;
use App\Services\Billing\PayPalBilling;
use App\Services\Billing\StripeBilling;
use App\Services\PlanGate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppearanceController extends Controller
{
    public function edit(PlanGate $gate)
    {
        $tenant = Tenant::current();
        $themes = $gate->allowedThemes($tenant);

        return view('admin.appearance.edit', compact('tenant', 'themes'));
    }

    public function update(Request $request, PlanGate $gate)
    {
        $tenant = Tenant::current();
        $allowed = $gate->allowedThemes($tenant);

        $data = $request->validate([
            'visual_theme' => ['required', Rule::in($allowed)],
        ]);

        $tenant->update(['visual_theme' => $data['visual_theme']]);
        AuditLog::record('tenant.theme_updated', $tenant, $data);

        return back()->with('success', __('app.flash.theme_saved'));
    }
}
