<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_a_cannot_see_tenant_b_versions_on_public_host(): void
    {
        $tenantA = Tenant::factory()->create(['slug' => 'alpha']);
        $tenantB = Tenant::factory()->create(['slug' => 'beta']);

        Tenant::setCurrent($tenantA);
        Version::create([
            'tenant_id' => $tenantA->id,
            'version_number' => '1.0.0-A',
            'release_date' => now()->toDateString(),
            'description' => 'Secret A',
            'content' => 'Content A',
        ]);

        Tenant::setCurrent($tenantB);
        Version::create([
            'tenant_id' => $tenantB->id,
            'version_number' => '1.0.0-B',
            'release_date' => now()->toDateString(),
            'description' => 'Secret B',
            'content' => 'Content B',
        ]);

        Tenant::forgetCurrent();

        $response = $this->get('http://alpha.localhost/changelog');
        $response->assertOk();
        $response->assertSee('1.0.0-A');
        $response->assertDontSee('1.0.0-B');
        $response->assertDontSee('Secret B');
    }

    public function test_unverified_custom_domain_returns_404(): void
    {
        Tenant::factory()->create([
            'slug' => 'gamma',
            'custom_domain' => 'changelog.example.test',
            'domain_status' => Tenant::DOMAIN_PENDING,
        ]);

        $this->get('http://changelog.example.test/changelog')->assertNotFound();
    }

    public function test_verified_custom_domain_serves_tenant_data(): void
    {
        $tenant = Tenant::factory()->withCustomDomain('changelog.example.test')->create([
            'slug' => 'delta',
        ]);

        Tenant::setCurrent($tenant);
        Version::create([
            'tenant_id' => $tenant->id,
            'version_number' => '9.9.9',
            'release_date' => now()->toDateString(),
            'description' => 'Custom domain release',
            'content' => 'Hello custom',
        ]);
        Tenant::forgetCurrent();

        $this->get('http://changelog.example.test/changelog')
            ->assertOk()
            ->assertSee('9.9.9');
    }

    public function test_client_admin_is_scoped_to_own_tenant(): void
    {
        $tenantA = Tenant::factory()->create(['slug' => 'one']);
        $tenantB = Tenant::factory()->create(['slug' => 'two']);

        $userA = User::factory()->create([
            'tenant_id' => $tenantA->id,
            'role' => User::ROLE_CLIENT,
        ]);

        Tenant::setCurrent($tenantB);
        $versionB = Version::create([
            'tenant_id' => $tenantB->id,
            'version_number' => '2.0.0-B',
            'release_date' => now()->toDateString(),
            'description' => 'Only B',
            'content' => 'B',
        ]);
        Tenant::forgetCurrent();

        $this->actingAs($userA)
            ->get('http://localhost/admin/changelog')
            ->assertOk()
            ->assertDontSee('2.0.0-B');

        $this->actingAs($userA)
            ->get('http://localhost/admin/changelog/'.$versionB->id.'/edit')
            ->assertNotFound();
    }

    public function test_ssl_ask_allows_only_verified_custom_domains(): void
    {
        Tenant::factory()->withCustomDomain('ok.example.test')->create();
        Tenant::factory()->create([
            'custom_domain' => 'pending.example.test',
            'domain_status' => Tenant::DOMAIN_PENDING,
        ]);

        $this->get('/api/ssl/ask?domain=ok.example.test')->assertOk();
        $this->get('/api/ssl/ask?domain=pending.example.test')->assertNotFound();
        $this->get('/api/ssl/ask?domain=unknown.example.test')->assertNotFound();
    }

    public function test_registration_creates_tenant_and_client(): void
    {
        $response = $this->post('http://localhost/register', [
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'project_name' => 'Alice App',
            'slug' => 'alice-app',
        ]);

        $response->assertRedirect(route('admin.domain.edit'));
        $this->assertDatabaseHas('tenants', ['slug' => 'alice-app']);
        $this->assertDatabaseHas('users', [
            'email' => 'alice@example.com',
            'role' => User::ROLE_CLIENT,
        ]);
    }
}
