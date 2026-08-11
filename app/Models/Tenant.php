<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    public const DOMAIN_NONE = 'none';

    public const DOMAIN_PENDING = 'pending';

    public const DOMAIN_VERIFIED = 'verified';

    public const THEMES = ['classic', 'midnight', 'editorial', 'aurora'];

    protected $fillable = [
        'name',
        'slug',
        'custom_domain',
        'domain_status',
        'domain_verification_token',
        'branding',
        'visual_theme',
        'is_active',
        'suspended_at',
        'suspension_reason',
        'plan_id',
        'feature_overrides',
    ];

    protected $casts = [
        'branding' => 'array',
        'feature_overrides' => 'array',
        'is_active' => 'boolean',
        'suspended_at' => 'datetime',
    ];

    protected static ?self $current = null;

    public static function current(): ?self
    {
        return static::$current;
    }

    public static function setCurrent(?self $tenant): void
    {
        static::$current = $tenant;

        if ($tenant) {
            app()->instance('tenant', $tenant);
        } elseif (app()->bound('tenant')) {
            app()->forgetInstance('tenant');
        }
    }

    public static function forgetCurrent(): void
    {
        static::setCurrent(null);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->whereIn('status', ['active', 'trialing'])->latestOfMany();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function isSuspended(): bool
    {
        return $this->suspended_at !== null;
    }

    public function effectivePlan(): ?Plan
    {
        if ($this->relationLoaded('plan') && $this->plan) {
            return $this->plan;
        }

        if ($this->plan_id) {
            return $this->plan()->first();
        }

        return Plan::where('slug', 'free')->first();
    }

    public function subdomainUrl(): string
    {
        $scheme = parse_url(config('app.url'), PHP_URL_SCHEME) ?: 'https';
        $central = config('tenancy.central_domain');

        return "{$scheme}://{$this->slug}.{$central}";
    }

    public function publicBaseUrl(): string
    {
        if ($this->domain_status === self::DOMAIN_VERIFIED && $this->custom_domain) {
            $scheme = parse_url(config('app.url'), PHP_URL_SCHEME) ?: 'https';

            return "{$scheme}://{$this->custom_domain}";
        }

        return $this->subdomainUrl();
    }

    public function ensureVerificationToken(): string
    {
        if (! $this->domain_verification_token) {
            $this->domain_verification_token = Str::random(40);
            $this->save();
        }

        return $this->domain_verification_token;
    }

    public function isCustomDomainVerified(): bool
    {
        return $this->domain_status === self::DOMAIN_VERIFIED
            && filled($this->custom_domain);
    }

    public function theme(): string
    {
        $theme = $this->visual_theme ?: 'classic';

        return in_array($theme, self::THEMES, true) ? $theme : 'classic';
    }
}
