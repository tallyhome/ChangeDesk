<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bug_reports', function (Blueprint $table) {
            // Make reporter_email nullable
            $table->string('reporter_email')->nullable()->change();
            
            // Modify status column to ensure it can hold values like 'open', 'in_progress', etc.
            $table->string('status', 20)->change();
        });
    }

    public function down(): void
    {
        Schema::table('bug_reports', function (Blueprint $table) {
            $table->string('reporter_email')->nullable(false)->change();
            $table->string('status')->change();
        });
    }
};