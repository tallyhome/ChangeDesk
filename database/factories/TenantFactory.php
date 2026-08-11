<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'custom_domain' => null,
            'domain_status' => Tenant::DOMAIN_NONE,
            'domain_verification_token' => Str::random(40),
            'branding' => null,
            'is_active' => true,
        ];
    }

    public function withCustomDomain(string $domain): static
    {
        return $this->state(fn () => [
            'custom_domain' => $domain,
            'domain_status' => Tenant::DOMAIN_VERIFIED,
        ]);
    }
}
