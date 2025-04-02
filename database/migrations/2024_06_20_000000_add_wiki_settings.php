<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Vérifier et ajouter les paramètres wiki manquants
        $wikiSettings = [
            ['key' => 'wiki_enabled', 'value' => '1'],
            ['key' => 'todo_enabled', 'value' => '1'],
            ['key' => 'wiki_title', 'value' => 'Wiki'],
            ['key' => 'wiki_welcome_title', 'value' => 'Bienvenue dans le Wiki'],
            ['key' => 'wiki_welcome_text', 'value' => "Bienvenue dans notre base de connaissances. Vous trouverez ici toutes les informations nécessaires organisées par catégories.\n\nUtilisez le menu de navigation à gauche pour parcourir les différentes catégories ou utilisez la barre de recherche pour trouver rapidement ce que vous cherchez."]
        ];
        
        foreach ($wikiSettings as $setting) {
            // Vérifier si le paramètre existe déjà
            $exists = DB::table('settings')->where('key', $setting['key'])->exists();
            
            if (!$exists) {
                DB::table('settings')->insert([
                    'key' => $setting['key'],
                    'value' => $setting['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        // Supprimer les paramètres wiki ajoutés
        DB::table('settings')->whereIn('key', [
            'wiki_enabled',
            'todo_enabled',
            'wiki_title',
            'wiki_welcome_title',
            'wiki_welcome_text'
        ])->delete();
    }
};