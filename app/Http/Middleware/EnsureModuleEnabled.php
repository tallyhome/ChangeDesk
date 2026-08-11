<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\PlanGate;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureModuleEnabled
{
    public function __construct(private PlanGate $gate)
    {
    }

    public function handle(Request $request, Closure $next, string $module): Response
    {
        $tenant = Tenant::current();
        if (! $this->gate->can($tenant, $module)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Module non inclus dans votre plan.'], 403);
            }

            return response()->view('themes.classic.pages.upsell', [
                'tenant' => $tenant,
                'module' => $module,
            ], 403);
        }

        return $next($request);
    }
}
