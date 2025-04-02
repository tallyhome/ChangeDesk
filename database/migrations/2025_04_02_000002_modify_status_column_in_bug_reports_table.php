<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier la colonne status pour accepter la valeur 'open'
        Schema::table('bug_reports', function (Blueprint $table) {
            // Supprimer la contrainte ENUM existante
            $table->string('status', 20)->change();
        });

        // Mettre à jour les valeurs existantes si nécessaire
        DB::statement("UPDATE bug_reports SET status = 'in_progress' WHERE status = 'new'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer la contrainte ENUM d'origine
        Schema::table('bug_reports', function (Blueprint $table) {
            $table->enum('status', ['new', 'in_progress', 'resolved', 'closed'])->default('new')->change();
        });
    }
};