<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run()
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'default'],
            [
                'name' => 'Demo Product',
                'domain_status' => Tenant::DOMAIN_NONE,
                'domain_verification_token' => Str::random(40),
                'is_active' => true,
            ]
        );

        Tenant::setCurrent($tenant);

        $pages = [
            [
                'title' => 'Accueil',
                'slug' => 'home',
                'content' => '<p>Bienvenue sur le changelog de <strong>Demo Product</strong>.</p><p>Ceci est un contenu d’exemple — remplacez-le par le vôtre depuis l’administration.</p>',
            ],
            [
                'title' => 'Conditions d\'utilisation',
                'slug' => 'terms',
                'content' => '<p>Exemple de conditions d’utilisation. À personnaliser.</p>',
            ],
            [
                'title' => 'Politique de confidentialité',
                'slug' => 'privacy',
                'content' => '<p>Exemple de politique de confidentialité. À personnaliser.</p>',
            ],
        ];

        foreach ($pages as $page) {
            Page::withoutGlobalScope('tenant')->updateOrCreate(
                ['tenant_id' => $tenant->id, 'slug' => $page['slug']],
                $page + ['tenant_id' => $tenant->id]
            );
        }
    }
}
