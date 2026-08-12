<?php

namespace App\Services;

use App\Models\BugReport;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TodoItem;
use App\Models\Version;
use App\Models\WikiArticle;
use App\Models\WikiCategory;
use Illuminate\Support\Str;

class TenantProvisioner
{
    public function create(string $name, string $slug, ?Plan $plan = null): Tenant
    {
        $plan ??= Plan::where('slug', 'free')->first();

        $tenant = Tenant::create([
            'name' => $name,
            'slug' => Str::slug($slug),
            'domain_status' => Tenant::DOMAIN_NONE,
            'domain_verification_token' => Str::random(40),
            'visual_theme' => 'classic',
            'is_active' => true,
            'plan_id' => $plan?->id,
        ]);

        if ($plan) {
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'provider' => 'manual',
                'current_period_start' => now(),
                'current_period_end' => $plan->slug === 'free' ? now()->addYears(10) : now()->addMonth(),
            ]);
        }

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
                'content' => '<p>Bienvenue sur le changelog de <strong>'.htmlspecialchars($tenant->name, ENT_QUOTES, 'UTF-8').'</strong>.</p><p>Personnalisez cette page depuis Administration → Pages. Les contenus d’exemple (versions, roadmap, bugs, wiki) sont déjà prêts à éditer ou supprimer.</p>',
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
            'wiki_welcome_text' => '<p>Documentation d’exemple — modifiez-la depuis l’administration.</p>',
        ];

        foreach ($defaults as $key => $value) {
            Setting::withoutGlobalScope('tenant')->updateOrCreate(
                ['tenant_id' => $tenant->id, 'key' => $key],
                ['value' => $value]
            );
        }

        $this->seedDemoContent($tenant);
    }

    /**
     * Contenu d’exemple pour les nouveaux comptes (changelog, roadmap, bugs, wiki).
     */
    public function seedDemoContent(Tenant $tenant): void
    {
        Tenant::setCurrent($tenant);
        $name = $tenant->name;

        if (! Version::withoutGlobalScope('tenant')->where('tenant_id', $tenant->id)->exists()) {
            Version::create([
                'version_number' => '1.0.0',
                'release_date' => now()->subMonths(2)->toDateString(),
                'description' => 'Première version publique (exemple)',
                'content' => '<ul><li>Lancement de <strong>'.e($name).'</strong></li><li>Page changelog</li><li>Espace administration</li></ul><p><em>Exemple — modifiez ou supprimez depuis Admin → Changelog.</em></p>',
            ]);
            Version::create([
                'version_number' => '1.1.0',
                'release_date' => now()->subWeeks(3)->toDateString(),
                'description' => 'Améliorations UX (exemple)',
                'content' => '<ul><li>Meilleure navigation</li><li>Corrections mineures</li><li>Thèmes publics</li></ul>',
            ]);
        }

        if (! TodoItem::withoutGlobalScope('tenant')->where('tenant_id', $tenant->id)->exists()) {
            TodoItem::create([
                'title' => 'Mode sombre public',
                'description' => 'Exemple de fonctionnalité à venir — à supprimer ou modifier.',
                'status' => 'in_progress',
                'progress' => 60,
                'color' => 'warning',
                'expected_date' => now()->addDays(20)->toDateString(),
            ]);
            TodoItem::create([
                'title' => 'Export PDF des releases',
                'description' => 'Exemple roadmap — contenu fictif.',
                'status' => 'pending',
                'progress' => 15,
                'color' => 'info',
                'expected_date' => now()->addDays(45)->toDateString(),
            ]);
            TodoItem::create([
                'title' => 'Notifications e-mail',
                'description' => 'Exemple terminé pour illustrer une barre verte.',
                'status' => 'completed',
                'progress' => 100,
                'color' => 'success',
                'expected_date' => now()->subDays(5)->toDateString(),
            ]);
        }

        if (! BugReport::withoutGlobalScope('tenant')->where('tenant_id', $tenant->id)->exists()) {
            BugReport::create([
                'title' => 'Bouton « Enregistrer » inactif (exemple)',
                'description' => 'Contenu fictif pour illustrer un rapport de bug. Supprimez-le depuis l’admin.',
                'reporter_name' => 'Alex Martin',
                'reporter_email' => 'alex@example.com',
                'status' => 'open',
                'progress' => 0,
                'color' => 'danger',
                'priority' => 'medium',
            ]);
            BugReport::create([
                'title' => 'Typo sur la page d’accueil (exemple)',
                'description' => 'Petit bug d’exemple déjà résolu.',
                'reporter_name' => 'Sam Leroy',
                'reporter_email' => 'sam@example.com',
                'status' => 'resolved',
                'progress' => 100,
                'color' => 'success',
                'priority' => 'low',
            ]);
        }

        if (! WikiCategory::withoutGlobalScope('tenant')->where('tenant_id', $tenant->id)->exists()) {
            $gettingStarted = WikiCategory::create([
                'name' => 'Démarrage',
                'slug' => 'demarrage',
                'description' => 'Premiers pas avec '.$name,
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
                'content' => '<p>Ceci est un article d’exemple pour <strong>'.e($name).'</strong>.</p>
<p>Vous pouvez le modifier ou le supprimer depuis l’administration → Wiki.</p>
<ul><li>Créez vos catégories</li><li>Rédigez vos guides</li><li>Publiez pour vos utilisateurs</li></ul>',
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
}
