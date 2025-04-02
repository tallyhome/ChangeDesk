<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('versions', function (Blueprint $table) {
            if (Schema::hasColumn('versions', 'number') && !Schema::hasColumn('versions', 'version_number')) {
                $table->renameColumn('number', 'version_number');
            }
            // Renommer la colonne 'changes' en 'content' si elle existe
            if (Schema::hasColumn('versions', 'changes') && !Schema::hasColumn('versions', 'content')) {
                $table->renameColumn('changes', 'content');
            }
        });
    }

    public function down(): void
    {
        Schema::table('versions', function (Blueprint $table) {
            if (Schema::hasColumn('versions', 'version_number')) {
                $table->renameColumn('version_number', 'number');
            }
            if (Schema::hasColumn('versions', 'content')) {
                $table->renameColumn('content', 'changes');
            }
        });
    }
};