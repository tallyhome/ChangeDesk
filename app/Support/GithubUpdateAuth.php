<?php

namespace App\Support;

/**
 * Dépôt des mises à jour. Le token se lit dans GITHUB_UPDATE_TOKEN (.env), jamais dans le code.
 * TOKEN reste un repli vide pour les installations déjà en place.
 */
final class GithubUpdateAuth
{
    public const REPO = 'tallyhome/ChangeDesk';

    public const API = 'https://api.github.com';

    public const TOKEN = '';

    public static function token(): string
    {
        $fromConfig = config('updates.github_token');
        if (is_string($fromConfig) && trim($fromConfig) !== '') {
            return trim($fromConfig);
        }

        return trim(self::TOKEN);
    }

    public static function hasToken(): bool
    {
        return self::token() !== '';
    }
}
