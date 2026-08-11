<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SslAskController extends Controller
{
    /**
     * Allowlist endpoint for Caddy on_demand_tls.
     * Returns 200 only for verified custom domains (or known tenant subdomains).
     */
    public function __invoke(Request $request): Response
    {
        $domain = strtolower(trim((string) $request->query('domain', '')));

        if ($domain === '') {
            return response('missing domain', 400);
        }

        $centralDomains = array_map('strtolower', config('tenancy.central_domains', []));
        $centralDomain = strtolower((string) config('tenancy.central_domain'));

        if (in_array($domain, $centralDomains, true)) {
            return response('ok', 200);
        }

        $suffix = '.'.$centralDomain;
        if ($centralDomain !== '' && str_ends_with($domain, $suffix)) {
            $slug = substr($domain, 0, -strlen($suffix));
            if ($slug !== '' && ! str_contains($slug, '.') && Tenant::where('slug', $slug)->where('is_active', true)->exists()) {
                return response('ok', 200);
            }
        }

        $allowed = Tenant::query()
            ->where('custom_domain', $domain)
            ->where('domain_status', Tenant::DOMAIN_VERIFIED)
            ->where('is_active', true)
            ->exists();

        return $allowed
            ? response('ok', 200)
            : response('forbidden', 404);
    }
}
