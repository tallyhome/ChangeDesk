<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Tenant;
use App\Services\DomainVerificationService;
use App\Services\PlanGate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DomainController extends Controller
{
    public function edit(Request $request)
    {
        $tenant = Tenant::current() ?: $request->user()->tenant;
        abort_unless($tenant, 404);

        return view('admin.domain.edit', [
            'tenant' => $tenant,
            'cnameTarget' => config('tenancy.cname_target'),
            'centralDomain' => config('tenancy.central_domain'),
        ]);
    }

    public function update(Request $request, PlanGate $gate)
    {
        $tenant = Tenant::current() ?: $request->user()->tenant;
        abort_unless($tenant, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:63',
                'alpha_dash',
                Rule::unique('tenants', 'slug')->ignore($tenant->id),
            ],
            'custom_domain' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)+$/i',
                Rule::unique('tenants', 'custom_domain')->ignore($tenant->id),
            ],
        ]);

        $slug = Str::slug($validated['slug']);
        if (in_array($slug, ['www', 'admin', 'api', 'mail', 'app', 'default', 'cname'], true)) {
            throw ValidationException::withMessages([
                'slug' => 'Ce sous-domaine n\'est pas disponible.',
            ]);
        }

        $customDomain = filled($validated['custom_domain'] ?? null)
            ? strtolower(trim($validated['custom_domain']))
            : null;

        if ($customDomain && ! $gate->can($tenant, 'custom_domain')) {
            throw ValidationException::withMessages([
                'custom_domain' => 'Le domaine personnalisé nécessite le plan Business.',
            ]);
        }

        $domainChanged = $customDomain !== $tenant->custom_domain;

        $tenant->fill([
            'name' => $validated['name'],
            'slug' => $slug,
            'custom_domain' => $customDomain,
        ]);

        if (! $customDomain) {
            $tenant->domain_status = Tenant::DOMAIN_NONE;
        } elseif ($domainChanged) {
            $tenant->domain_status = Tenant::DOMAIN_PENDING;
            $tenant->domain_verification_token = Str::random(40);
        }

        $tenant->save();
        AuditLog::record('tenant.domain_updated', $tenant, $validated);

        return redirect()
            ->route('admin.domain.edit')
            ->with('success', 'Paramètres de domaine enregistrés.');
    }

    public function verify(Request $request, DomainVerificationService $verifier, PlanGate $gate)
    {
        $tenant = Tenant::current() ?: $request->user()->tenant;
        abort_unless($tenant, 404);

        if (! $gate->can($tenant, 'custom_domain')) {
            return redirect()->route('admin.domain.edit')
                ->withErrors(['custom_domain' => 'Le domaine personnalisé nécessite le plan Business.']);
        }

        if (! $tenant->custom_domain) {
            return redirect()
                ->route('admin.domain.edit')
                ->withErrors(['custom_domain' => 'Ajoutez d\'abord un domaine personnalisé.']);
        }

        $ok = $verifier->verify($tenant->fresh());
        AuditLog::record($ok ? 'tenant.domain_verified' : 'tenant.domain_verify_failed', $tenant);

        return redirect()
            ->route('admin.domain.edit')
            ->with(
                $ok ? 'success' : 'error',
                $ok
                    ? 'Domaine vérifié avec succès. Il est maintenant actif.'
                    : 'Vérification échouée. Vérifiez le CNAME ou l\'enregistrement TXT, puis réessayez.'
            );
    }
}
