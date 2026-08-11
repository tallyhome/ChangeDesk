<?php

namespace Database\Seeders;

use App\Models\BugReport;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class BugReportSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::where('slug', 'default')->first();
        if ($tenant) {
            Tenant::setCurrent($tenant);
        }

        if (BugReport::withoutGlobalScope('tenant')->where('tenant_id', $tenant?->id)->exists()) {
            return;
        }

        BugReport::create([
            'title' => 'Bouton « Enregistrer » inactif (exemple)',
            'description' => 'Contenu fictif pour illustrer un rapport de bug. Supprimez-le depuis l’admin.',
            'reporter_name' => 'Alex Martin',
            'reporter_email' => 'alex@example.com',
            'status' => 'open',
            'progress' => 0,
            'color' => 'danger',
            'severity' => 'medium',
        ]);

        BugReport::create([
            'title' => 'Typo sur la page d’accueil (exemple)',
            'description' => 'Petit bug d’exemple déjà résolu.',
            'reporter_name' => 'Sam Leroy',
            'reporter_email' => 'sam@example.com',
            'status' => 'resolved',
            'progress' => 100,
            'color' => 'success',
            'severity' => 'low',
            'admin_notes' => 'Corrigé dans la version 1.1.0.',
        ]);
    }
}
