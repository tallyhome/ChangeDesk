<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Tenant;
use App\Models\User;
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
            ->with('tenant')
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
        $tenants = Tenant::orderBy('name')->get();

        return view('superadmin.users.form', [
            'user' => new User(['role' => User::ROLE_CLIENT, 'is_active' => true]),
            'tenants' => $tenants,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', Rule::in([User::ROLE_CLIENT, User::ROLE_SUPERADMIN])],
            'tenant_id' => ['nullable', 'exists:tenants,id'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['role'] === User::ROLE_SUPERADMIN) {
            $validated['tenant_id'] = null;
        } elseif (empty($validated['tenant_id'])) {
            return back()->withErrors(['tenant_id' => 'Un client doit être rattaché à un tenant.'])->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'tenant_id' => $validated['tenant_id'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::record('user.created', $user->tenant, ['user_id' => $user->id]);

        return redirect()->route('superadmin.users.edit', $user)->with('success', 'Utilisateur créé.');
    }

    public function edit(User $user)
    {
        $tenants = Tenant::orderBy('name')->get();

        return view('superadmin.users.form', compact('user', 'tenants'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in([User::ROLE_CLIENT, User::ROLE_SUPERADMIN])],
            'tenant_id' => ['nullable', 'exists:tenants,id'],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        if ($validated['role'] === User::ROLE_SUPERADMIN) {
            $validated['tenant_id'] = null;
        } elseif (empty($validated['tenant_id'])) {
            return back()->withErrors(['tenant_id' => 'Un client doit être rattaché à un tenant.'])->withInput();
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'tenant_id' => $validated['tenant_id'] ?? null,
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
