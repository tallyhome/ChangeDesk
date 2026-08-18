<?php

/**
 * Smoke isolation checks without PHPUnit (run: php tests/smoke_tenancy.php)
 */

use App\Models\Tenant;
use App\Models\User;
use App\Models\Version;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

function assert_true(bool $cond, string $msg): void
{
    if (! $cond) {
        fwrite(STDERR, "FAIL: {$msg}\n");
        exit(1);
    }
    echo "OK: {$msg}\n";
}

$suffix = Str::lower(Str::random(6));

$tenantA = Tenant::create([
    'name' => 'Smoke A',
    'slug' => 'smoke-a-'.$suffix,
    'domain_status' => Tenant::DOMAIN_NONE,
    'domain_verification_token' => Str::random(40),
    'is_active' => true,
]);
$tenantB = Tenant::create([
    'name' => 'Smoke B',
    'slug' => 'smoke-b-'.$suffix,
    'domain_status' => Tenant::DOMAIN_NONE,
    'domain_verification_token' => Str::random(40),
    'is_active' => true,
]);

Tenant::setCurrent($tenantA);
Version::create([
    'version_number' => 'A-ONLY',
    'release_date' => now()->toDateString(),
    'description' => 'A',
    'content' => 'A',
]);

Tenant::setCurrent($tenantB);
Version::create([
    'version_number' => 'B-ONLY',
    'release_date' => now()->toDateString(),
    'description' => 'B',
    'content' => 'B',
]);

Tenant::setCurrent($tenantA);
$seen = Version::pluck('version_number')->all();
assert_true($seen === ['A-ONLY'], 'tenant A scope hides B versions');

Tenant::setCurrent($tenantB);
$seen = Version::pluck('version_number')->all();
assert_true($seen === ['B-ONLY'], 'tenant B scope hides A versions');

$user = User::create([
    'name' => 'Smoke Client',
    'email' => 'smoke-'.$suffix.'@example.com',
    'password' => Hash::make('password'),
    'role' => User::ROLE_CLIENT,
    'tenant_id' => $tenantA->id,
]);
assert_true($user->isClient(), 'client role helper');

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/api/ssl/ask?domain=unknown.example.test', 'GET');
$response = $kernel->handle($request);
assert_true($response->getStatusCode() === 404, 'ssl ask rejects unknown domain');

$tenantA->update([
    'custom_domain' => 'smoke-'.$suffix.'.example.test',
    'domain_status' => Tenant::DOMAIN_VERIFIED,
]);
$request = Illuminate\Http\Request::create('/api/ssl/ask?domain=smoke-'.$suffix.'.example.test', 'GET');
$response = $kernel->handle($request);
assert_true($response->getStatusCode() === 200, 'ssl ask allows verified custom domain');

$central = Illuminate\Http\Request::create('http://localhost/', 'GET');
$centralResponse = $kernel->handle($central);
assert_true($centralResponse->getStatusCode() === 200, 'central landing is reachable');
assert_true(str_contains($centralResponse->getContent(), 'Evolora') || str_contains($centralResponse->getContent(), 'plans'), 'central landing shows brand');

Tenant::forgetCurrent();
$sub = Illuminate\Http\Request::create('http://'.$tenantA->slug.'.localhost/changelog', 'GET');
$subResponse = $kernel->handle($sub);
assert_true($subResponse->getStatusCode() === 200, 'tenant subdomain changelog reachable');
assert_true(str_contains($subResponse->getContent(), 'A-ONLY'), 'tenant subdomain shows own changelog');
assert_true(! str_contains($subResponse->getContent(), 'B-ONLY'), 'tenant subdomain hides other changelog');

echo "All smoke checks passed.\n";
