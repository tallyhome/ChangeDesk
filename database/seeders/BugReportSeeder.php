<?php

namespace Database\Seeders;

use App\Models\BugReport;
use Illuminate\Database\Seeder;

class BugReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BugReport::create([
            'title' => 'Erreur lors de la connexion',
            'description' => 'Lorsque je tente de me connecter avec des identifiants valides, je reçois parfois une erreur 500.',
            'reporter_name' => 'Jean Dupont',
            'reporter_email' => 'jean.dupont@example.com',
            'status' => 'resolved',
            'admin_notes' => 'Problème résolu - Il s\'agissait d\'un problème de cache du serveur.',
        ]);

        BugReport::create([
            'title' => 'Problème d\'affichage sur Firefox',
            'description' => 'Le menu déroulant ne s\'affiche pas correctement sur Firefox version 95.',
            'reporter_name' => 'Marie Martin',
            'reporter_email' => 'marie.martin@example.com',
            'status' => 'in_progress',
            'admin_notes' => 'En cours d\'investigation - Problème de compatibilité CSS.',
        ]);

        BugReport::create([
            'title' => 'Impossible de télécharger des fichiers PDF',
            'description' => 'Lorsque j\'essaie de télécharger un fichier PDF, rien ne se passe.',
            'reporter_name' => 'Pierre Leroy',
            'reporter_email' => 'pierre.leroy@example.com',
            'status' => 'new',
            'admin_notes' => null,
        ]);

        BugReport::create([
            'title' => 'Lenteur lors du chargement des images',
            'description' => 'Les images mettent beaucoup de temps à se charger sur la page d\'accueil.',
            'reporter_name' => 'Sophie Dubois',
            'reporter_email' => 'sophie.dubois@example.com',
            'status' => 'new',
            'admin_notes' => null,
        ]);

        BugReport::create([
            'title' => 'Erreur 404 sur le lien de contact',
            'description' => 'Le lien vers la page de contact dans le pied de page renvoie une erreur 404.',
            'reporter_name' => 'Thomas Bernard',
            'reporter_email' => 'thomas.bernard@example.com',
            'status' => 'resolved',
            'admin_notes' => 'Corrigé - URL mal formée dans le template.',
        ]);
    }
}
