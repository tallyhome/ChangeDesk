<?php

/**
 * Smoke: plans, gating, themes, suspension, billing activate
 * php tests/smoke_billing_themes.php
 */

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Version;
use App\Services\Billing\StripeBilling;
use App\Services\PlanGate;
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

$gate = app(PlanGate::class);
$free = Plan::where('slug', 'free')->first();
$pro = Plan::where('slug', 'pro')->first();
$business = Plan::where('slug', 'business')->first();
assert_true($free && $pro && $business, '3 plans seeded');

$suffix = Str::lower(Str::random(5));
$tenant = Tenant::create([
    'name' => 'Smoke Billing',
    'slug' => 'smoke-bill-'.$suffix,
    'visual_theme' => 'classic',
    'plan_id' => $free->id,
    'is_active' => true,
    'domain_status' => 'none',
]);

assert_true($gate->can($tenant, 'changelog'), 'free has changelog');
assert_true(! $gate->can($tenant, 'wiki'), 'free lacks wiki');
assert_true(! $gate->can($tenant, 'custom_domain'), 'free lacks custom domain');
assert_true($gate->can($tenant, 'theme', 'classic'), 'free allows classic');
assert_true(! $gate->can($tenant, 'theme', 'aurora'), 'free blocks aurora');

app(StripeBilling::class)->activate($tenant, $pro, 'manual', 'manual-smoke-'.$suffix);
$tenant->refresh();
assert_true($tenant->plan_id === $pro->id, 'activated to pro');
assert_true($gate->can($tenant->fresh(), 'wiki'), 'pro has wiki');
assert_true($gate->can($tenant->fresh(), 'theme', 'midnight'), 'pro has midnight');
assert_true(! $gate->can($tenant->fresh(), 'theme', 'aurora'), 'pro blocks aurora');

app(StripeBilling::class)->activate($tenant, $business, 'manual', 'manual-biz-'.$suffix);
assert_true($gate->can($tenant->fresh(), 'custom_domain'), 'business has custom domain');
assert_true($gate->can($tenant->fresh(), 'theme', 'aurora'), 'business has aurora');

$tenant->update(['visual_theme' => 'midnight', 'suspended_at' => now(), 'suspension_reason' => 'Test suspension']);
Tenant::setCurrent($tenant->fresh());
Version::create([
    'version_number' => '9.9.9',
    'release_date' => now()->toDateString(),
    'description' => 'Theme smoke',
    'content' => 'x',
]);

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$res = $kernel->handle(Illuminate\Http\Request::create('http://'.$tenant->slug.'.localhost/changelog', 'GET'));
assert_true($res->getStatusCode() === 403, 'suspended tenant public returns 403');
assert_true(str_contains($res->getContent(), 'Test suspension'), 'suspension message shown');

$tenant->update(['suspended_at' => null, 'suspension_reason' => null]);
Tenant::forgetCurrent();
$res2 = $kernel->handle(Illuminate\Http\Request::create('http://'.$tenant->slug.'.localhost/changelog', 'GET'));
assert_true($res2->getStatusCode() === 200, 'unsuspended midnight changelog ok');
assert_true(str_contains($res2->getContent(), '9.9.9') || str_contains($res2->getContent(), 'Mises à jour'), 'midnight theme content');

$landing = $kernel->handle(Illuminate\Http\Request::create('http://localhost/', 'GET'));
assert_true($landing->getStatusCode() === 200, 'vitrine ok');
assert_true(str_contains($landing->getContent(), 'Evolora') || str_contains($landing->getContent(), 'plans') || str_contains($landing->getContent(), 'Free'), 'vitrine shows brand/plans');

$payload = [
    'type' => 'checkout.session.completed',
    'data' => ['object' => [
        'id' => 'cs_test_'.$suffix,
        'client_reference_id' => (string) $tenant->id,
        'metadata' => ['tenant_id' => (string) $tenant->id, 'plan_id' => (string) $pro->id],
        'subscription' => 'sub_test_'.$suffix,
        'customer' => 'cus_test',
    ]],
];
app(StripeBilling::class)->handleWebhook($payload);
assert_true($tenant->fresh()->plan_id === $pro->id, 'stripe webhook activates plan');

echo "All billing/theme smoke checks passed.\n";
