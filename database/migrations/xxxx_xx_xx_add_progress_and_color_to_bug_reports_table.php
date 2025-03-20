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
        Schema::table('bug_reports', function (Blueprint $table) {
            // Vérifier si les colonnes n'existent pas déjà avant de les ajouter
            if (!Schema::hasColumn('bug_reports', 'progress')) {
                $table->integer('progress')->default(0)->after('status');
            }
            
            if (!Schema::hasColumn('bug_reports', 'color')) {
                $table->string('color')->default('danger')->after('progress');
            }
            
            if (!Schema::hasColumn('bug_reports', 'severity')) {
                $table->string('severity')->default('medium')->after('color');
            }
            
            if (!Schema::hasColumn('bug_reports', 'expected_fix_date')) {
                $table->date('expected_fix_date')->nullable()->after('severity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bug_reports', function (Blueprint $table) {
            $table->dropColumn(['progress', 'color', 'severity', 'expected_fix_date']);
        });
    }
};