<?php

namespace App\Support;

/**
 * Identifiants MAJ GitHub — volontairement hors .env (embarqués dans le produit).
 *
 * Pour un dépôt privé : crée un Fine-grained PAT GitHub
 * (Settings → Developer settings → Fine-grained tokens) avec uniquement :
 * - Repository access : tallyhome/ChangeDesk
 * - Permissions : Contents = Read-only, Metadata = Read-only
 * Puis colle le token dans TOKEN ci-dessous.
 */
final class GithubUpdateAuth
{
    public const REPO = 'tallyhome/ChangeDesk';

    public const API = 'https://api.github.com';

    /**
     * PAT lecture seule. Laisser vide si le dépôt est public.
     * Exemple : github_pat_xxxxxxxx ou ghp_xxxxxxxx
     */
    public const TOKEN = '';

    public static function token(): string
    {
        return trim(self::TOKEN);
    }

    public static function hasToken(): bool
    {
        return self::token() !== '';
    }
}
