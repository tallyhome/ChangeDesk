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
            // Ajouter les colonnes à la fin de la table au lieu de spécifier "after"
            if (!Schema::hasColumn('todo_items', 'status')) {
                $table->string('status')->default('pending');
            }
            
            if (!Schema::hasColumn('todo_items', 'expected_date')) {
                $table->date('expected_date')->nullable();
            }
            
            if (!Schema::hasColumn('todo_items', 'progress')) {
                $table->integer('progress')->default(0);
            }
            
            if (!Schema::hasColumn('todo_items', 'color')) {
                $table->string('color')->default('primary');
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