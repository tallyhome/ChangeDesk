<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class Locale
{
    public static function all(): array
    {
        return config('locales.supported', []);
    }

    public static function codes(): array
    {
        return array_keys(static::all());
    }

    public static function isSupported(?string $locale): bool
    {
        return $locale !== null && in_array($locale, static::codes(), true);
    }

    public static function current(): string
    {
        $locale = app()->getLocale();

        return static::isSupported($locale) ? $locale : (string) config('locales.default', 'fr');
    }

    public static function meta(?string $locale = null): array
    {
        $locale = $locale ?: static::current();

        return static::all()[$locale] ?? [
            'name' => strtoupper($locale),
            'native' => strtoupper($locale),
            'flag' => '🌐',
            'dir' => 'ltr',
        ];
    }

    public static function dateFormat(?string $locale = null): string
    {
        $locale = $locale ?: static::current();

        return config('locales.date.'.$locale, 'd/m/Y');
    }

    public static function formatDate(mixed $date, ?string $locale = null): string
    {
        if (! $date) {
            return '';
        }

        if (is_string($date)) {
            try {
                $date = Carbon::parse($date);
            } catch (\Throwable) {
                return $date;
            }
        }

        if (! $date instanceof CarbonInterface) {
            return (string) $date;
        }

        return $date->translatedFormat(static::dateFormat($locale));
    }
}
