<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyStatusColumnInBugReportsTable extends Migration
{
    public function up()
    {
        Schema::table('bug_reports', function (Blueprint $table) {
            $table->string('status', 50)->change(); // Augmenter la taille
        });
    }

    public function down()
    {
        Schema::table('bug_reports', function (Blueprint $table) {
            $table->string('status', 20)->change(); // Revenir à la taille originale si nécessaire
        });
    }
}