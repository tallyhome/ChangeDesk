<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todo_items', function (Blueprint $table) {
            if (!Schema::hasColumn('todo_items', 'status')) {
                $table->string('status')->default('in_progress');
            }
            if (!Schema::hasColumn('todo_items', 'progress')) {
                $table->integer('progress')->default(0);
            }
            if (!Schema::hasColumn('todo_items', 'color')) {
                $table->string('color')->default('primary');
            }
        });
    }

    public function down(): void
    {
        Schema::table('todo_items', function (Blueprint $table) {
            $table->dropColumn(['status', 'progress', 'color']);
        });
    }
};