<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Setting;
use App\Models\Tenant;
use Illuminate\Support\Str;

class TenantProvisioner
{
    public function create(string $name, string $slug): Tenant
    {
        $tenant = Tenant::create([
            'name' => $name,
            'slug' => Str::slug($slug),
            'domain_status' => Tenant::DOMAIN_NONE,
            'domain_verification_token' => Str::random(40),
            'is_active' => true,
        ]);

        $this->seedDefaults($tenant);

        return $tenant;
    }

    public function seedDefaults(Tenant $tenant): void
    {
        Tenant::setCurrent($tenant);

        Page::withoutGlobalScope('tenant')->insert([
            [
                'tenant_id' => $tenant->id,
                'title' => 'Accueil',
                'content' => 'Bienvenue sur notre changelog.',
                'slug' => 'home',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'title' => 'Conditions d\'utilisation',
                'content' => 'En utilisant ce site...',
                'slug' => 'terms',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenant->id,
                'title' => 'Politique de confidentialité',
                'content' => 'Protection de vos données...',
                'slug' => 'privacy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $defaults = [
            'external_link_enabled' => '0',
            'external_link_text' => '',
            'external_link_url' => '',
            'app_store_enabled' => '0',
            'app_store_url' => '',
            'play_store_enabled' => '0',
            'play_store_url' => '',
            'bug_report_enabled' => '1',
            'wiki_enabled' => '1',
            'todo_enabled' => '1',
            'changelog_enabled' => '1',
            'wiki_title' => 'Wiki',
            'wiki_welcome_title' => 'Bienvenue dans le Wiki',
            'wiki_welcome_text' => '',
        ];

        foreach ($defaults as $key => $value) {
            Setting::withoutGlobalScope('tenant')->updateOrCreate(
                ['tenant_id' => $tenant->id, 'key' => $key],
                ['value' => $value]
            );
        }
    }
}
