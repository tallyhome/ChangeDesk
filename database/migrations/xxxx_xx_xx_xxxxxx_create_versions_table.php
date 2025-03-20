<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('versions')) {
            Schema::create('versions', function (Blueprint $table) {
                $table->id();
                $table->string('version_number');
                $table->date('release_date');
                $table->string('description');
                $table->text('content');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('versions');
    }
}