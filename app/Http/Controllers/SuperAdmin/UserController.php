<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Billing\StripeBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->with(['tenant.plan', 'preferredPlan'])
            ->when($request->q, fn ($q) => $q->where(function ($qq) use ($request) {
                $qq->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%');
            }))
            ->when($request->role, fn ($q) => $q->where('role', $request->role))
            ->when($request->status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        return view('superadmin.users.form', [
            'user' => new User(['role' => User::ROLE_CLIENT, 'is_active' => true]),
            'tenants' => Tenant::orderBy('name')->get(),
            'plans' => Plan::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', Rule::in([User::ROLE_CLIENT, User::ROLE_SUPERADMIN])],
            'tenant_id' => ['nullable', 'exists:tenants,id'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $tenantId = null;
        $preferredPlanId = null;

        if ($validated['role'] === User::ROLE_SUPERADMIN) {
            $tenantId = null;
            $preferredPlanId = null;
        } else {
            $tenantId = $validated['tenant_id'] ?? null;
            $planId = $validated['plan_id'] ?? Plan::where('slug', 'free')->value('id');

            if ($tenantId) {
                $tenant = Tenant::findOrFail($tenantId);
                if ($planId) {
                    $plan = Plan::findOrFail($planId);
                    app(StripeBilling::class)->activate($tenant, $plan, 'manual', 'manual-user-'.Str::uuid());
                }
            } else {
                // Client sans projet : le plan est stocké pour l'onboarding
                $preferredPlanId = $planId;
            }
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'tenant_id' => $tenantId,
            'preferred_plan_id' => $preferredPlanId,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::record('user.created', $user->tenant, [
            'user_id' => $user->id,
            'preferred_plan_id' => $preferredPlanId,
        ]);

        return redirect()->route('superadmin.users.edit', $user)->with('success', 'Utilisateur créé.');
    }

    public function edit(User $user)
    {
        $user->load(['tenant.plan', 'preferredPlan']);

        return view('superadmin.users.form', [
            'user' => $user,
            'tenants' => Tenant::orderBy('name')->get(),
            'plans' => Plan::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in([User::ROLE_CLIENT, User::ROLE_SUPERADMIN])],
            'tenant_id' => ['nullable', 'exists:tenants,id'],
            'plan_id' => ['nullable', 'exists:plans,id'],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ], [
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $tenantId = null;
        $preferredPlanId = $user->preferred_plan_id;

        if ($validated['role'] === User::ROLE_SUPERADMIN) {
            $tenantId = null;
            $preferredPlanId = null;
        } else {
            $tenantId = $validated['tenant_id'] ?? null;
            $planId = $validated['plan_id'] ?? null;

            if ($tenantId && $planId) {
                $tenant = Tenant::findOrFail($tenantId);
                $plan = Plan::findOrFail($planId);
                app(StripeBilling::class)->activate($tenant, $plan, 'manual', 'manual-user-'.Str::uuid());
                $preferredPlanId = null;
            } elseif (! $tenantId && $planId) {
                $preferredPlanId = $planId;
            } elseif ($tenantId) {
                $preferredPlanId = null;
            }
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'tenant_id' => $tenantId,
            'preferred_plan_id' => $preferredPlanId,
            'is_active' => $request->boolean('is_active'),
        ]);

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();
        AuditLog::record('user.updated', $user->tenant, ['user_id' => $user->id]);

        return redirect()->route('superadmin.users.edit', $user)->with('success', 'Utilisateur mis à jour.');
    }

    public function resetPassword(User $user)
    {
        $temp = Str::password(12);
        $user->update(['password' => Hash::make($temp)]);
        AuditLog::record('user.password_reset', $user->tenant, ['user_id' => $user->id]);

        return back()->with('success', "Nouveau mot de passe temporaire : {$temp}");
    }

    public function toggleActive(User $user)
    {
        $user->update(['is_active' => ! $user->is_active]);
        AuditLog::record('user.toggle_active', $user->tenant, ['is_active' => $user->is_active]);

        return back()->with('success', $user->is_active ? 'Utilisateur activé.' : 'Utilisateur désactivé.');
    }
}
