<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('todo_items', function (Blueprint $table) {
            // Vérifier si les colonnes existent déjà avant de les ajouter
            if (!Schema::hasColumn('todo_items', 'status')) {
                $table->string('status')->default('pending')->after('description');
            }
            
            // If expected_date column doesn't exist, add it too
            if (!Schema::hasColumn('todo_items', 'expected_date')) {
                $table->date('expected_date')->nullable()->after('description');
            }
            
            // Ajout de la colonne pour le pourcentage de progression
            if (!Schema::hasColumn('todo_items', 'progress')) {
                $table->integer('progress')->default(0)->after('description');
            }
            
            // Ajout de la colonne pour la couleur de la barre de progression
            if (!Schema::hasColumn('todo_items', 'color')) {
                $table->string('color')->default('primary')->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todo_items', function (Blueprint $table) {
            // Vérifier si les colonnes existent avant de les supprimer
            if (Schema::hasColumn('todo_items', 'status')) {
                $table->dropColumn('status');
            }
            
            if (Schema::hasColumn('todo_items', 'progress')) {
                $table->dropColumn('progress');
            }
            
            if (Schema::hasColumn('todo_items', 'color')) {
                $table->dropColumn('color');
            }
            
            if (Schema::hasColumn('todo_items', 'expected_date')) {
                $table->dropColumn('expected_date');
            }
        });
    }
};