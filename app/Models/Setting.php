<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];
    
    /**
     * Récupère la valeur d'un paramètre par sa clé
     */
    public static function getValue($key, $default = null)
    {
        $cacheKey = 'setting_' . $key;
        $value = cache()->get($cacheKey);
        
        if ($value === null) {
            $setting = self::where('key', $key)->first();
            $value = $setting ? $setting->value : $default;
            cache()->put($cacheKey, $value, now()->addDay());
        }
        
        return $value;
    }
    
    /**
     * Vide le cache après la mise à jour d'un paramètre
     */
    public static function boot()
    {
        parent::boot();
        
        static::saved(function ($setting) {
            cache()->forget('setting_' . $setting->key);
        });
    }
}