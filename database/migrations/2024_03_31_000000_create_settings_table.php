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

        // Insérer les paramètres par défaut
        DB::table('settings')->insert([
            ['key' => 'bug_report_enabled', 'value' => '1'],
            ['key' => 'wiki_enabled', 'value' => '1'],
            ['key' => 'todo_enabled', 'value' => '1'],
            ['key' => 'external_link_enabled', 'value' => '0'],
            ['key' => 'external_link_url', 'value' => ''],
            ['key' => 'external_link_text', 'value' => ''],
            ['key' => 'wiki_title', 'value' => 'Wiki'],
            ['key' => 'wiki_welcome_title', 'value' => 'Bienvenue dans le Wiki'],
            ['key' => 'wiki_welcome_text', 'value' => "Bienvenue dans notre base de connaissances. Vous trouverez ici toutes les informations nécessaires organisées par catégories.\n\nUtilisez le menu de navigation à gauche pour parcourir les différentes catégories ou utilisez la barre de recherche pour trouver rapidement ce que vous cherchez."],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}; 