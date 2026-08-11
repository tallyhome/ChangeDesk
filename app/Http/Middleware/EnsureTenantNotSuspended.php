<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantNotSuspended
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Tenant::current();
        if ($tenant && $tenant->isSuspended()) {
            return response()->view('themes.classic.pages.suspended', [
                'tenant' => $tenant,
            ], 403);
        }

        return $next($request);
    }
}
