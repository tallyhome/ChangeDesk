<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run()
    {
        Page::create([
            'title' => 'Accueil',
            'content' => 'Bienvenue sur notre site web...',
            'slug' => 'home'
        ]);

//        Changelog a été remanier avec un system de version, donc plus besoin qu'il soit dans les pages        
 //       Page::create([
 //           'title' => 'Changelog',
 //           'content' => 'Version 1.0.0 - Lancement initial...',
 //           'slug' => 'changelog'
 //       ]);

        Page::create([
            'title' => 'Conditions d\'utilisation',
            'content' => 'En utilisant ce site...',
            'slug' => 'terms'
        ]);

        Page::create([
            'title' => 'Politique de confidentialité',
            'content' => 'Protection de vos données...',
            'slug' => 'privacy'
        ]);
    }
}