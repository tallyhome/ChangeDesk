<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('todo_items', function (Blueprint $table) {
            // Ajoutez ici les colonnes manquantes
            if (!Schema::hasColumn('todo_items', 'title')) {
                $table->string('title');
            }
            if (!Schema::hasColumn('todo_items', 'description')) {
                $table->text('description');
            }
            if (!Schema::hasColumn('todo_items', 'priority')) {
                $table->integer('priority')->default(0);
            }
            if (!Schema::hasColumn('todo_items', 'completion_percentage')) {
                $table->integer('completion_percentage')->default(0);
            }
            if (!Schema::hasColumn('todo_items', 'estimated_completion_date')) {
                $table->date('estimated_completion_date')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('todo_items', function (Blueprint $table) {
            // Supprimez les colonnes ajoutées
            $table->dropColumn([
                'title',
                'description',
                'priority',
                'completion_percentage',
                'estimated_completion_date'
            ]);
        });
    }
};