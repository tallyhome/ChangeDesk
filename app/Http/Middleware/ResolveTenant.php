<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        Tenant::forgetCurrent();

        $host = strtolower($request->getHost());
        $centralDomains = array_map('strtolower', config('tenancy.central_domains', []));
        $centralDomain = strtolower((string) config('tenancy.central_domain'));

        if (in_array($host, $centralDomains, true)) {
            return $next($request);
        }

        $tenant = null;

        $suffix = '.'.$centralDomain;
        if ($centralDomain !== '' && str_ends_with($host, $suffix)) {
            $slug = substr($host, 0, -strlen($suffix));
            if ($slug !== '' && ! str_contains($slug, '.')) {
                $tenant = Tenant::query()
                    ->where('slug', $slug)
                    ->where('is_active', true)
                    ->first();
            }
        }

        if (! $tenant) {
            $tenant = Tenant::query()
                ->where('custom_domain', $host)
                ->where('domain_status', Tenant::DOMAIN_VERIFIED)
                ->where('is_active', true)
                ->first();
        }

        if (! $tenant) {
            abort(404, 'Projet introuvable pour ce domaine.');
        }

        Tenant::setCurrent($tenant);
        view()->share('currentTenant', $tenant);

        return $next($request);
    }
}
