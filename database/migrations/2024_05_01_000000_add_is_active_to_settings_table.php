<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Ajouter les paramètres d'activation pour chaque lien externe
        DB::table('settings')->insert([
            'key' => 'external_link_active',
            'value' => '1', // Activé par défaut
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        DB::table('settings')->insert([
            'key' => 'app_store_active',
            'value' => '1', // Activé par défaut
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        DB::table('settings')->insert([
            'key' => 'play_store_active',
            'value' => '1', // Activé par défaut
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        // Supprimer les paramètres d'activation
        DB::table('settings')->where('key', 'external_link_active')->delete();
        DB::table('settings')->where('key', 'app_store_active')->delete();
        DB::table('settings')->where('key', 'play_store_active')->delete();
    }
};