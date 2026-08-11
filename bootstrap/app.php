<?php

use App\Http\Middleware\EnsureCentralDomain;
use App\Http\Middleware\EnsureClientUser;
use App\Http\Middleware\EnsureSuperAdmin;
use App\Http\Middleware\EnsureTenantHost;
use App\Http\Middleware\ResolveTenant;
use App\Http\Middleware\SetTenantFromAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            ResolveTenant::class,
        ]);

        $middleware->alias([
            'central' => EnsureCentralDomain::class,
            'tenant.host' => EnsureTenantHost::class,
            'tenant.fromAuth' => SetTenantFromAuth::class,
            'client' => EnsureClientUser::class,
            'superadmin' => EnsureSuperAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
