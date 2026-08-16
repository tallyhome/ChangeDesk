<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'price_cents',
        'interval',
        'stripe_price_id',
        'paypal_plan_id',
        'features',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price_cents' => 'integer',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function hasFeature(string $feature): bool
    {
        $features = $this->features ?? [];

        if (array_key_exists($feature, $features)) {
            return (bool) $features[$feature];
        }

        if (isset($features['modules']) && is_array($features['modules'])) {
            return in_array($feature, $features['modules'], true);
        }

        return false;
    }

    public function allowedThemes(): array
    {
        return $this->features['themes'] ?? ['classic'];
    }

    public function formattedPrice(): string
    {
        if ($this->price_cents <= 0) {
            return __('app.common.free');
        }

        return number_format($this->price_cents / 100, 2, ',', ' ').' € / '.$this->interval;
    }
}
