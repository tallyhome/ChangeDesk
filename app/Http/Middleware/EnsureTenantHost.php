<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantHost
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Tenant::current()) {
            abort(404);
        }

        return $next($request);
    }
}
