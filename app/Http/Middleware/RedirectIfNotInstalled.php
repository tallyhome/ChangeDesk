<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('install') || $request->is('install/*') || $request->is('up')) {
            return $next($request);
        }

        // Déjà installé explicitement
        if (File::exists(storage_path('app/installed'))) {
            return $next($request);
        }

        // Déploiement existant (.env + clé) : ne pas forcer l'installeur
        if (File::exists(base_path('.env')) && filled(config('app.key'))) {
            return $next($request);
        }

        return redirect('/install');
    }
}
