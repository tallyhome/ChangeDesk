<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    public const DOMAIN_NONE = 'none';

    public const DOMAIN_PENDING = 'pending';

    public const DOMAIN_VERIFIED = 'verified';

    protected $fillable = [
        'name',
        'slug',
        'custom_domain',
        'domain_status',
        'domain_verification_token',
        'branding',
        'is_active',
    ];

    protected $casts = [
        'branding' => 'array',
        'is_active' => 'boolean',
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
}
