<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\BelongsToTenant;

class Setting extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = ['tenant_id', 'key', 'value'];

    public static function getValue($key, $default = null)
    {
        $tenant = Tenant::current();
        if (! $tenant) {
            return $default;
        }

        $cacheKey = 'setting_'.$tenant->id.'_'.$key;
        $value = cache()->get($cacheKey);

        if ($value === null) {
            $setting = static::where('key', $key)->first();
            $value = $setting ? $setting->value : $default;
            cache()->put($cacheKey, $value, now()->addDay());
        }

        return $value;
    }

    public static function setValue(string $key, $value): self
    {
        $tenant = Tenant::current();
        abort_unless($tenant, 500, 'Aucun tenant courant pour les paramètres.');

        return static::updateOrCreate(
            ['tenant_id' => $tenant->id, 'key' => $key],
            ['value' => $value]
        );
    }

    public static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            if ($setting->tenant_id) {
                cache()->forget('setting_'.$setting->tenant_id.'_'.$setting->key);
            }
        });
    }
}
