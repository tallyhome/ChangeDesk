<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run()
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'default'],
            [
                'name' => 'Default Project',
                'domain_status' => Tenant::DOMAIN_NONE,
                'is_active' => true,
            ]
        );

        Tenant::setCurrent($tenant);

        $pages = [
            ['title' => 'Accueil', 'content' => 'Bienvenue sur notre site web...', 'slug' => 'home'],
            ['title' => 'Conditions d\'utilisation', 'content' => 'En utilisant ce site...', 'slug' => 'terms'],
            ['title' => 'Politique de confidentialité', 'content' => 'Protection de vos données...', 'slug' => 'privacy'],
        ];

        foreach ($pages as $page) {
            Page::withoutGlobalScope('tenant')->updateOrCreate(
                ['tenant_id' => $tenant->id, 'slug' => $page['slug']],
                $page + ['tenant_id' => $tenant->id]
            );
        }
    }
}
