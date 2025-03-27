<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
        
        // Insérer le paramètre pour le lien externe
        DB::table('settings')->insert([
            'key' => 'external_link_url',
            'value' => 'https://myvcard.fr/',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Insérer le paramètre pour le texte du lien
        DB::table('settings')->insert([
            'key' => 'external_link_text',
            'value' => 'MyVCard',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Paramètres pour les liens des stores
        DB::table('settings')->insert([
            'key' => 'app_store_url',
            'value' => 'https://apps.apple.com/app/id123456789',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        DB::table('settings')->insert([
            'key' => 'play_store_url',
            'value' => 'https://play.google.com/store/apps/details?id=com.example.app',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};