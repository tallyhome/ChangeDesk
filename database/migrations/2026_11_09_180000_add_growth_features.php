<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'project_role')) {
                $table->string('project_role', 20)->default('owner')->after('role');
            }
        });

        Schema::create('changelog_subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('token', 64)->unique();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'email']);
        });

        Schema::create('bug_watchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bug_report_id')->constrained('bug_reports')->cascadeOnDelete();
            $table->string('email');
            $table->string('token', 64)->unique();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            $table->unique(['bug_report_id', 'email']);
        });

        Schema::create('roadmap_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('todo_item_id')->constrained('todo_items')->cascadeOnDelete();
            $table->string('voter_token', 64);
            $table->timestamps();
            $table->unique(['todo_item_id', 'voter_token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roadmap_votes');
        Schema::dropIfExists('bug_watchers');
        Schema::dropIfExists('changelog_subscribers');
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'project_role')) {
                $table->dropColumn('project_role');
            }
        });
    }
};
