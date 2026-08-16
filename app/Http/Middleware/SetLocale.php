<?php

namespace App\Http\Middleware;

use App\Support\Locale;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolve($request);

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        if ($request->hasSession() && $request->session()->get('locale') !== $locale) {
            $request->session()->put('locale', $locale);
        }

        $response = $next($request);

        if (! $request->cookies->has('locale') || $request->cookie('locale') !== $locale) {
            $response->headers->setCookie(cookie('locale', $locale, 60 * 24 * 365));
        }

        return $response;
    }

    private function resolve(Request $request): string
    {
        $candidates = [
            $request->query('lang'),
            $request->hasSession() ? $request->session()->get('locale') : null,
            $request->cookie('locale'),
            $request->getPreferredLanguage(Locale::codes()),
            config('locales.default', 'fr'),
        ];

        foreach ($candidates as $candidate) {
            if (Locale::isSupported(is_string($candidate) ? strtolower($candidate) : null)) {
                $locale = strtolower((string) $candidate);

                if ($request->query('lang') === $locale && $request->hasSession()) {
                    $request->session()->put('locale', $locale);
                }

                return $locale;
            }
        }

        return 'fr';
    }
}
