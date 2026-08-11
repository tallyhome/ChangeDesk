<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCentralDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());
        $centralDomains = array_map('strtolower', config('tenancy.central_domains', []));

        if (! in_array($host, $centralDomains, true)) {
            $scheme = $request->getScheme();
            $central = config('tenancy.central_domain');

            return redirect()->to("{$scheme}://{$central}".$request->getRequestUri());
        }

        return $next($request);
    }
}
