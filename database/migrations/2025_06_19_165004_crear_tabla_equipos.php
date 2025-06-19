<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->enum('tipo', ['MOTOR_ELECTRICO', 'MAQUINA_SOLDADORA', 'GENERADOR_DINAMO', 'OTROS']);
            $table->string('marca');
            $table->string('color')->nullable();
            $table->string('numero_serie')->nullable();
            $table->string('potencia')->nullable(); // Para OTROS
            $table->string('voltaje')->nullable(); // Común a varios tipos
            
            // Campos específicos para MOTOR_ELECTRICO
            $table->string('hp')->nullable();
            $table->string('rpm')->nullable();
            $table->string('hz')->nullable();
            
            // Campos específicos para MAQUINA_SOLDADORA
            $table->string('amperaje')->nullable();
            $table->string('cable_positivo')->nullable();
            $table->string('cable_negativo')->nullable();
            
            // Campos específicos para GENERADOR_DINAMO
            $table->string('kva_kw')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipos');
    }
};
