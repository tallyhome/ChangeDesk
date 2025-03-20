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
        // Vérifier si la table existe avant de la modifier
        if (Schema::hasTable('bug_reports')) {
            Schema::table('bug_reports', function (Blueprint $table) {
                // Vérifier si les colonnes n'existent pas déjà avant de les ajouter
                if (!Schema::hasColumn('bug_reports', 'progress')) {
                    $table->integer('progress')->default(0);
                }
                
                if (!Schema::hasColumn('bug_reports', 'color')) {
                    $table->string('color')->default('danger');
                }
                
                if (!Schema::hasColumn('bug_reports', 'severity')) {
                    $table->string('severity')->default('medium');
                }
                
                if (!Schema::hasColumn('bug_reports', 'expected_fix_date')) {
                    $table->date('expected_fix_date')->nullable();
                }
                
                if (!Schema::hasColumn('bug_reports', 'reporter_name')) {
                    $table->string('reporter_name')->nullable(); // Permettre NULL
                }
                
                if (!Schema::hasColumn('bug_reports', 'reporter_email')) {
                    $table->string('reporter_email')->nullable(); // Permettre NULL
                }
            });
        } else {
            // Créer la table si elle n'existe pas
            Schema::create('bug_reports', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->string('status')->default('open');
                $table->integer('progress')->default(0);
                $table->string('color')->default('danger');
                $table->string('severity')->default('medium');
                $table->date('expected_fix_date')->nullable();
                $table->string('reporter_name')->nullable();
                $table->string('reporter_email')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('bug_reports')) {
            Schema::table('bug_reports', function (Blueprint $table) {
                $table->dropColumn(['progress', 'color', 'severity', 'expected_fix_date', 'reporter_name', 'reporter_email']);
            });
        }
    }
};
