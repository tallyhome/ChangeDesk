<?php

namespace App\Providers;

use App\Models\Tenant;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('appVersion', Config::get('updates.number', Config::get('version.number')));

        View::composer('*', function ($view) {
            $view->with('currentTenant', Tenant::current());
        });

        // Cookie de session partagé apex + sous-domaines (login central ↔ site tenant)
        if (! Config::get('session.domain')) {
            $central = Config::get('tenancy.central_domain');
            if (is_string($central) && $central !== '' && ! in_array($central, ['localhost', '127.0.0.1'], true)) {
                Config::set('session.domain', '.'.$central);
            }
        }
    }
}