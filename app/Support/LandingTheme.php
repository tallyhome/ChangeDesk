<?php

namespace App\Support;

use App\Models\PlatformSetting;
use Illuminate\Support\Facades\View;

class LandingTheme
{
    public const SETTING_KEY = 'landing_theme';

    public const DEFAULT = 'origin';

    /**
     * @return array<string, array{label:string, description:string, accent:string}>
     */
    public static function all(): array
    {
        return [
            'origin' => [
                'label' => 'Origin',
                'description' => 'Crème & terracotta, carrousel produit — le thème historique.',
                'accent' => '#c2410c',
            ],
            'nebula' => [
                'label' => 'Nebula',
                'description' => 'Sombre tech, dégradés cyan/violet et cartes en verre.',
                'accent' => '#22d3ee',
            ],
            'studio' => [
                'label' => 'Studio',
                'description' => 'Clair éditorial, grandes typos et blocs encadrés.',
                'accent' => '#111827',
            ],
        ];
    }

    public static function slugs(): array
    {
        return array_keys(static::all());
    }

    public static function isValid(?string $slug): bool
    {
        return $slug !== null && in_array($slug, static::slugs(), true);
    }

    public static function current(): string
    {
        $slug = PlatformSetting::getValue(self::SETTING_KEY, config('landing.theme', self::DEFAULT));

        return static::isValid($slug) ? $slug : self::DEFAULT;
    }

    public static function set(string $slug): void
    {
        if (! static::isValid($slug)) {
            return;
        }

        PlatformSetting::setValue(self::SETTING_KEY, $slug);
    }

    public static function view(?string $slug = null): string
    {
        $slug = static::isValid($slug) ? $slug : static::current();

        // Le thème historique reste sur la vue d'origine
        if ($slug === self::DEFAULT) {
            return 'central.landing';
        }

        $candidate = "central.landings.{$slug}";

        return View::exists($candidate) ? $candidate : 'central.landing';
    }

    public static function label(?string $slug): string
    {
        return static::all()[$slug]['label'] ?? ucfirst((string) $slug);
    }
}
