<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Visit;
use Illuminate\Http\Request;

class RecordVisit
{
    public function handle(Request $request, Closure $next)
    {
        // Ne pas enregistrer les visites pour les assets et les requêtes AJAX
        if (!$request->is('api/*') && 
            !$request->is('_debugbar/*') && 
            !$this->isAsset($request->path()) && 
            !$request->ajax()) {
            Visit::recordVisit($request);
        }

        return $next($request);
    }

    private function isAsset($path)
    {
        $assetExtensions = ['js', 'css', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'svg'];
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        return in_array(strtolower($extension), $assetExtensions);
    }
}