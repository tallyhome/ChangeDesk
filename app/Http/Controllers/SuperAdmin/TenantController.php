<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Billing\StripeBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::query()
            ->with(['plan'])
            ->withCount('users')
            ->latest()
            ->paginate(20);

        return view('superadmin.tenants.index', compact('tenants'));
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['users', 'plan', 'subscriptions.plan', 'payments']);
        $plans = Plan::orderBy('sort_order')->get();

        return view('superadmin.tenants.show', compact('tenant', 'plans'));
    }

    public function edit(Tenant $tenant)
    {
        $plans = Plan::orderBy('sort_order')->get();

        return view('superadmin.tenants.edit', compact('tenant', 'plans'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:63', Rule::unique('tenants', 'slug')->ignore($tenant->id)],
            'custom_domain' => ['nullable', 'string', 'max:255', Rule::unique('tenants', 'custom_domain')->ignore($tenant->id)],
            'domain_status' => ['required', 'in:none,pending,verified'],
            'visual_theme' => ['required', Rule::in(Tenant::THEMES)],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'branding_primary' => ['nullable', 'string', 'max:32'],
        ]);

        $branding = $tenant->branding ?? [];
        if (! empty($validated['branding_primary'])) {
            $branding['primary'] = $validated['branding_primary'];
        }

        $tenant->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'custom_domain' => $validated['custom_domain'] ?: null,
            'domain_status' => $validated['domain_status'],
            'visual_theme' => $validated['visual_theme'],
            'plan_id' => $validated['plan_id'] ?? $tenant->plan_id,
            'is_active' => $request->boolean('is_active'),
            'branding' => $branding,
        ]);

        if (! empty($validated['plan_id'])) {
            app(StripeBilling::class)->activate(
                $tenant,
                Plan::findOrFail($validated['plan_id']),
                'manual',
                'manual-'.Str::uuid()
            );
        }

        AuditLog::record('tenant.updated', $tenant, $validated);

        return redirect()->route('superadmin.tenants.show', $tenant)->with('success', __('app.flash.tenant_updated'));
    }

    public function toggle(Tenant $tenant)
    {
        $tenant->update(['is_active' => ! $tenant->is_active]);
        AuditLog::record('tenant.toggle', $tenant, ['is_active' => $tenant->is_active]);

        return back()->with('success', $tenant->is_active ? __('app.flash.tenant_enabled') : __('app.flash.tenant_disabled'));
    }

    public function suspend(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'suspension_reason' => ['required', 'string', 'max:1000'],
        ]);

        $tenant->update([
            'suspended_at' => now(),
            'suspension_reason' => $data['suspension_reason'],
        ]);

        AuditLog::record('tenant.suspended', $tenant, $data);

        return back()->with('success', __('app.flash.tenant_suspended'));
    }

    public function unsuspend(Tenant $tenant)
    {
        $tenant->update([
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        AuditLog::record('tenant.unsuspended', $tenant);

        return back()->with('success', __('app.flash.tenant_unsuspended'));
    }

    public function impersonate(Tenant $tenant)
    {
        $client = $tenant->users()->where('role', User::ROLE_CLIENT)->where('is_active', true)->first();
        abort_unless($client, 404, 'Aucun client actif pour ce tenant.');

        request()->session()->put('impersonator_id', Auth::id());
        Auth::login($client);
        Tenant::setCurrent($tenant);

        AuditLog::record('impersonation.start', $tenant, ['client_id' => $client->id]);

        return redirect()->route('admin.dashboard')->with('success', __('app.flash.impersonating', ['email' => $client->email]));
    }
}
