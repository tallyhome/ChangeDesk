<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantFromAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isClient() && $user->tenant_id) {
            $tenant = $user->tenant ?: Tenant::find($user->tenant_id);
            if (! $tenant || ! $tenant->is_active) {
                abort(403, 'Votre projet est indisponible.');
            }
            Tenant::setCurrent($tenant);
            view()->share('currentTenant', $tenant);
        }

        return $next($request);
    }
}
