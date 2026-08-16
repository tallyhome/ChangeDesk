<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;

class RecordVisit
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Uniquement sur les sites publics tenant (pas central / admin / install)
        if (! Tenant::current()) {
            return $response;
        }

        if ($request->is('admin*')
            || $request->is('superadmin*')
            || $request->is('install*')
            || $request->is('login')
            || $request->is('register')
            || $request->is('api/*')
            || $request->is('theme-preview/*')
            || $request->is('locale/*')
            || $request->is('_debugbar/*')
            || $request->ajax()
            || $this->isAsset($request->path())) {
            return $response;
        }

        try {
            Visit::recordVisit($request);
        } catch (\Throwable $e) {
            report($e);
        }

        return $response;
    }

    private function isAsset(string $path): bool
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($extension, ['js', 'css', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'woff', 'woff2', 'map'], true);
    }
}
