<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recepcion_equipo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recepcion_id')->constrained('recepciones');
            $table->foreignId('equipo_id')->constrained('equipos');
            $table->text('observaciones')->nullable();
            $table->enum('estado_equipo', ['BUENO', 'REGULAR', 'MALO'])->default('BUENO');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recepcion_equipo');
    }
};
