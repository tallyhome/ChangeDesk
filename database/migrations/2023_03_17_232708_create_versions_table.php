<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->id();
            // Ajoutez la colonne 'number' si elle n'existe pas
            $table->string('number');  // Cette ligne doit être présente
            $table->date('release_date');
            $table->string('description');
            $table->text('changes');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('versions');
    }
};