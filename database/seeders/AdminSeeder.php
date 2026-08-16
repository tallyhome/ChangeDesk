<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'default'],
            [
                'name' => 'Demo Product',
                'domain_status' => Tenant::DOMAIN_NONE,
                'domain_verification_token' => Str::random(40),
                'visual_theme' => 'classic',
                'is_active' => true,
            ]
        );

        if ($tenant->name === 'Default Project' || str_contains(strtolower($tenant->name), 'myvcard') || str_contains(strtolower($tenant->name), 'mypredict')) {
            $tenant->update(['name' => 'Demo Product']);
        }

        User::updateOrCreate(
            ['email' => 'demo@chanlog.app'],
            [
                'name' => 'Demo Admin',
                'password' => 'password',
                'role' => User::ROLE_CLIENT,
                'tenant_id' => $tenant->id,
                'is_active' => true,
            ]
        );

        // Compat anciens comptes démo
        if ($legacy = User::where('email', 'admin@admin.com')->first()) {
            $legacy->update([
                'name' => 'Demo Admin',
                'role' => User::ROLE_CLIENT,
                'tenant_id' => $tenant->id,
            ]);
        }

        // Unifier l’ancien email migration → superadmin@chanlog.app
        $legacySa = User::where('email', 'superadmin@changelog.fr')->first();
        if ($legacySa && ! User::where('email', 'superadmin@chanlog.app')->exists()) {
            $legacySa->update([
                'email' => 'superadmin@chanlog.app',
                'name' => 'Super Admin',
                'password' => 'password',
                'role' => User::ROLE_SUPERADMIN,
                'tenant_id' => null,
                'is_active' => true,
            ]);
        } elseif ($legacySa) {
            $legacySa->update([
                'role' => User::ROLE_SUPERADMIN,
                'tenant_id' => null,
                'is_active' => true,
            ]);
        }

        User::updateOrCreate(
            ['email' => 'superadmin@chanlog.app'],
            [
                'name' => 'Super Admin',
                'password' => 'password',
                'role' => User::ROLE_SUPERADMIN,
                'tenant_id' => null,
                'is_active' => true,
            ]
        );
    }
}
