<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Réglages globaux de la plateforme (hors tenant) : thème vitrine, etc.
 */
class PlatformSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        try {
            if (! Schema::hasTable('platform_settings')) {
                return $default;
            }

            $row = static::query()->where('key', $key)->first();

            return $row?->value ?? $default;
        } catch (Throwable) {
            // Base non migrée / indisponible : on retombe sur la valeur par défaut
            return $default;
        }
    }

    public static function setValue(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
