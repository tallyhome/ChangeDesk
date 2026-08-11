<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'default'],
            [
                'name' => 'Default Project',
                'domain_status' => Tenant::DOMAIN_NONE,
                'domain_verification_token' => \Illuminate\Support\Str::random(40),
                'is_active' => true,
            ]
        );

        if (! User::where('email', 'admin@admin.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_CLIENT,
                'tenant_id' => $tenant->id,
            ]);
        } else {
            User::where('email', 'admin@admin.com')->update([
                'role' => User::ROLE_CLIENT,
                'tenant_id' => $tenant->id,
            ]);
        }

        if (! User::where('email', 'superadmin@changelog.fr')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@changelog.fr',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SUPERADMIN,
                'tenant_id' => null,
            ]);
        }
    }
}
