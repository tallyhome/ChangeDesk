<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantProvisioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProjectOnboardingController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        if ($user->tenant_id) {
            return redirect()->route('admin.dashboard');
        }

        $plan = $user->preferredPlan ?: Plan::where('slug', 'free')->first();

        return view('admin.onboarding.create', compact('plan'));
    }

    public function store(Request $request, TenantProvisioner $provisioner)
    {
        /** @var User $user */
        $user = $request->user();
        if ($user->tenant_id) {
            return redirect()->route('admin.dashboard');
        }

        $validated = $request->validate([
            'project_name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:63', 'alpha_dash', 'unique:tenants,slug'],
        ]);

        $slug = Str::slug($validated['slug']);
        if ($slug === '' || in_array($slug, ['www', 'admin', 'api', 'mail', 'app', 'default', 'cname'], true)) {
            throw ValidationException::withMessages([
                'slug' => 'Ce sous-domaine n\'est pas disponible.',
            ]);
        }

        $plan = $user->preferredPlan ?: Plan::where('slug', 'free')->first();

        DB::transaction(function () use ($user, $validated, $slug, $provisioner, $plan) {
            $tenant = $provisioner->create($validated['project_name'], $slug, $plan);
            $user->update(['tenant_id' => $tenant->id]);
            Tenant::setCurrent($tenant);
            AuditLog::record('tenant.created_by_client', $tenant, ['user_id' => $user->id]);
        });

        return redirect()
            ->route('admin.domain.edit')
            ->with('success', 'Projet créé. Configurez votre domaine si besoin.');
    }
}
