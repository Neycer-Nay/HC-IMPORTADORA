<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recepciones', function (Blueprint $table) {
            $table->id();
            $table->string('numero_recepcion')->unique();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('user_id')->constrained('users'); // Usuario que registra
            $table->dateTime('fecha_hora')->useCurrent();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['RECIBIDO', 'EN_REVISION', 'DIAGNOSTICADO', 'EN_REPARACION', 'REPARADO', 'ENTREGADO'])->default('RECIBIDO');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recepciones');
    }
};
