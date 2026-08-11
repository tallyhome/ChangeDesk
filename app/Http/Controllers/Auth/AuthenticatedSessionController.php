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

        $user = Auth::user();
        if (! $user->is_active) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Ce compte est désactivé.',
            ]);
        }

        $request->session()->regenerate();

        $central = strtolower((string) config('tenancy.central_domain'));
        $scheme = parse_url(config('app.url'), PHP_URL_SCHEME) ?: $request->getScheme();
        $adminBase = "{$scheme}://{$central}";

        if ($user->isSuperAdmin()) {
            Tenant::forgetCurrent();

            return redirect()->away($adminBase.'/superadmin');
        }

        if ($user->isClient() && $user->tenant) {
            if (! $user->tenant->is_active) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Votre projet est désactivé. Contactez le support.',
                ]);
            }

            Tenant::setCurrent($user->tenant);

            return redirect()->away($adminBase.'/admin');
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

        $central = strtolower((string) config('tenancy.central_domain'));
        $scheme = parse_url(config('app.url'), PHP_URL_SCHEME) ?: 'https';

        return redirect()->away("{$scheme}://{$central}/");
    }
}
