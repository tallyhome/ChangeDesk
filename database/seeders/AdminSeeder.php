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
            ['email' => 'demo@evolora.app'],
            [
                'name' => 'Demo Admin',
                'password' => 'password',
                'role' => User::ROLE_CLIENT,
                'tenant_id' => $tenant->id,
                'is_active' => true,
            ]
        );

        if ($legacyDemo = User::whereIn('email', ['demo@chanlog.app', 'admin@admin.com'])->first()) {
            if ($legacyDemo->email !== 'demo@evolora.app' && ! User::where('email', 'demo@evolora.app')->where('id', '!=', $legacyDemo->id)->exists()) {
                $legacyDemo->update([
                    'email' => 'demo@evolora.app',
                    'name' => 'Demo Admin',
                    'role' => User::ROLE_CLIENT,
                    'tenant_id' => $tenant->id,
                ]);
            }
        }

        $targetSa = 'superadmin@evolora.app';
        $legacySa = User::whereIn('email', ['superadmin@changelog.fr', 'superadmin@chanlog.app'])->first();
        if ($legacySa && $legacySa->email !== $targetSa && ! User::where('email', $targetSa)->exists()) {
            $legacySa->update([
                'email' => $targetSa,
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
            ['email' => $targetSa],
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
