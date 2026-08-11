<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
            ])->withInput();
        }

        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            Tenant::forgetCurrent();

            return redirect()->intended(route('superadmin.tenants.index'));
        }

        if ($user->isClient() && $user->tenant) {
            if (! $user->tenant->is_active) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Votre projet est désactivé. Contactez le support.',
                ]);
            }

            Tenant::setCurrent($user->tenant);

            return redirect()->intended(route('admin.dashboard'));
        }

        Auth::logout();

        return back()->withErrors([
            'email' => 'Compte non autorisé.',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Tenant::forgetCurrent();

        return redirect('/');
    }
}
