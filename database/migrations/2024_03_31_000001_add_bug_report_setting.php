<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Vérifier si le paramètre existe déjà
        $exists = DB::table('settings')->where('key', 'bug_report_enabled')->exists();
        
        if (!$exists) {
            DB::table('settings')->insert([
                'key' => 'bug_report_enabled',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        DB::table('settings')->where('key', 'bug_report_enabled')->delete();
    }
}; 