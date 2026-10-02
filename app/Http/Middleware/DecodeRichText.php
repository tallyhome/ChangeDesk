<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Rétablit le HTML des éditeurs envoyé en base64.
 * L'encodage évite qu'un style (font-size: 18pt, etc.) fasse rejeter le POST
 * par ModSecurity, ce qui se traduit par une 405.
 */
class DecodeRichText
{
    private const PREFIX = 'evolora64:';

    /** @var list<string> */
    private const FIELDS = ['content', 'description'];

    public function handle(Request $request, Closure $next): Response
    {
        $decoded = [];

        foreach (self::FIELDS as $field) {
            $value = $request->input($field);
            if (! is_string($value) || ! str_starts_with($value, self::PREFIX)) {
                continue;
            }

            $html = base64_decode(substr($value, strlen(self::PREFIX)), true);
            if ($html === false || ! mb_check_encoding($html, 'UTF-8')) {
                continue;
            }

            $decoded[$field] = $html;
        }

        if ($decoded !== []) {
            $request->merge($decoded);
        }

        return $next($request);
    }
}
