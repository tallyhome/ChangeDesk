<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantProvisioner;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request, TenantProvisioner $provisioner)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'project_name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:63', 'alpha_dash', 'unique:tenants,slug'],
        ]);

        $slug = Str::slug($validated['slug']);
        if ($slug === '' || in_array($slug, ['www', 'admin', 'api', 'mail', 'app', 'default', 'cname'], true)) {
            throw ValidationException::withMessages([
                'slug' => 'Ce sous-domaine n\'est pas disponible.',
            ]);
        }

        $user = DB::transaction(function () use ($validated, $slug, $provisioner) {
            $tenant = $provisioner->create($validated['project_name'], $slug);

            return User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => User::ROLE_CLIENT,
                'tenant_id' => $tenant->id,
            ]);
        });

        event(new Registered($user));
        Auth::login($user);

        Tenant::setCurrent($user->tenant);

        return redirect()
            ->route('admin.domain.edit')
            ->with('success', 'Bienvenue ! Votre projet est prêt. Configurez votre domaine personnalisé.');
    }
}
