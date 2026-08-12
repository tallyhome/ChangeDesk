<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Services\TenantProvisioner;
use Illuminate\Database\Seeder;

class WikiSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'default')->first();
        if (! $tenant) {
            return;
        }

        app(TenantProvisioner::class)->seedDemoContent($tenant);
    }
}
