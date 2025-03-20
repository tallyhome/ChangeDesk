<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpectedFixDateToBugReportsTable extends Migration
{
    public function up()
    {
        Schema::table('bug_reports', function (Blueprint $table) {
            $table->timestamp('expected_fix_date')->nullable(); // Ajoute la colonne
        });
    }

    public function down()
    {
        Schema::table('bug_reports', function (Blueprint $table) {
            $table->dropColumn('expected_fix_date'); // Supprime la colonne si nécessaire
        });
    }
}