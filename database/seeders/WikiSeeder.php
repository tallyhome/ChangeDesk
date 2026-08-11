<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\WikiArticle;
use App\Models\WikiCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WikiSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'default')->first();
        if (! $tenant) {
            return;
        }

        Tenant::setCurrent($tenant);

        if (WikiCategory::withoutGlobalScope('tenant')->where('tenant_id', $tenant->id)->exists()) {
            return;
        }

        $gettingStarted = WikiCategory::create([
            'name' => 'Démarrage',
            'slug' => 'demarrage',
            'description' => 'Premiers pas avec Demo Product',
            'order' => 1,
        ]);

        $faq = WikiCategory::create([
            'name' => 'FAQ',
            'slug' => 'faq',
            'description' => 'Questions fréquentes (exemple)',
            'order' => 2,
        ]);

        WikiArticle::create([
            'title' => 'Bienvenue dans le wiki',
            'slug' => 'bienvenue',
            'wiki_category_id' => $gettingStarted->id,
            'order' => 1,
            'is_published' => true,
            'content' => "<p>Ceci est un article d’exemple pour <strong>Demo Product</strong>.</p>
<p>Vous pouvez le modifier ou le supprimer depuis l’administration → Wiki.</p>
<ul><li>Créez vos catégories</li><li>Rédigez vos guides</li><li>Publiez pour vos utilisateurs</li></ul>",
        ]);

        WikiArticle::create([
            'title' => 'Comment signaler un bug ?',
            'slug' => 'signaler-un-bug',
            'wiki_category_id' => $faq->id,
            'order' => 1,
            'is_published' => true,
            'content' => '<p>Exemple FAQ : utilisez la page <em>Signaler un bug</em> du menu public, décrivez le problème, puis validez.</p>',
        ]);
    }
}
