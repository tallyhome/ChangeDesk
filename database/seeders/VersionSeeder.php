<?php

namespace Database\Seeders;

use App\Models\Version;
use Illuminate\Database\Seeder;

class VersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Version::create([
            'version_number' => '1.0.0',  // Utilisation du nom correct de la colonne
            'release_date' => '2023-01-15',
            'description' => 'Version initiale de l\'application',
            'content' => '<ul><li>Lancement de la plateforme</li><li>Système d\'authentification</li><li>Interface utilisateur de base</li></ul>',
        ]);

        Version::create([
            'version_number' => '1.1.0',
            'release_date' => '2023-02-20',
            'description' => 'Amélioration de l\'interface utilisateur',
            'content' => '<ul><li>Refonte du tableau de bord</li><li>Ajout de thèmes personnalisables</li><li>Optimisation des performances</li></ul>'
        ]);

        Version::create([
            'version_number' => '1.2.0',
            'release_date' => '2023-03-25',
            'description' => 'Nouvelles fonctionnalités',
            'content' => '<ul><li>Système de notifications</li><li>Export de données en PDF</li><li>Intégration avec des services tiers</li></ul>'
        ]);
    }
}