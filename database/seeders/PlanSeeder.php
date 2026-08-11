<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'slug' => 'free',
                'name' => 'Free',
                'price_cents' => 0,
                'interval' => 'month',
                'sort_order' => 1,
                'features' => [
                    'changelog' => true,
                    'todolist' => false,
                    'bugs' => false,
                    'wiki' => false,
                    'pages' => true,
                    'stats' => false,
                    'custom_domain' => false,
                    'branding' => false,
                    'priority_support' => false,
                    'themes' => ['classic'],
                ],
            ],
            [
                'slug' => 'pro',
                'name' => 'Pro',
                'price_cents' => 2900,
                'interval' => 'month',
                'sort_order' => 2,
                'stripe_price_id' => env('STRIPE_PRICE_PRO'),
                'paypal_plan_id' => env('PAYPAL_PLAN_PRO'),
                'features' => [
                    'changelog' => true,
                    'todolist' => true,
                    'bugs' => true,
                    'wiki' => true,
                    'pages' => true,
                    'stats' => true,
                    'custom_domain' => false,
                    'branding' => false,
                    'priority_support' => false,
                    'themes' => ['classic', 'midnight'],
                ],
            ],
            [
                'slug' => 'business',
                'name' => 'Business',
                'price_cents' => 7900,
                'interval' => 'month',
                'sort_order' => 3,
                'stripe_price_id' => env('STRIPE_PRICE_BUSINESS'),
                'paypal_plan_id' => env('PAYPAL_PLAN_BUSINESS'),
                'features' => [
                    'changelog' => true,
                    'todolist' => true,
                    'bugs' => true,
                    'wiki' => true,
                    'pages' => true,
                    'stats' => true,
                    'custom_domain' => true,
                    'branding' => true,
                    'priority_support' => true,
                    'themes' => ['classic', 'midnight', 'editorial', 'aurora'],
                ],
            ],
        ];

        foreach ($plans as $data) {
            Plan::updateOrCreate(['slug' => $data['slug']], $data);
        }

        $free = Plan::where('slug', 'free')->first();
        Tenant::query()->whereNull('plan_id')->update(['plan_id' => $free->id]);

        Tenant::query()->each(function (Tenant $tenant) use ($free) {
            $hasSub = Subscription::where('tenant_id', $tenant->id)
                ->whereIn('status', ['active', 'trialing'])
                ->exists();

            if (! $hasSub) {
                Subscription::create([
                    'tenant_id' => $tenant->id,
                    'plan_id' => $tenant->plan_id ?: $free->id,
                    'status' => 'active',
                    'provider' => 'manual',
                    'current_period_start' => now(),
                    'current_period_end' => now()->addYears(10),
                ]);
            }
        });
    }
}
