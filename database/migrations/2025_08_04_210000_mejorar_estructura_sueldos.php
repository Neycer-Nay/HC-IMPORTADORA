<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar tabla sueldos para mejorar la lógica de pagos mensuales con anticipos
        Schema::table('sueldos', function (Blueprint $table) {
            // Agregar columnas para mejor control de pagos
            $table->enum('tipo_pago', ['salario_completo', 'anticipo', 'pago_final'])->default('salario_completo')->after('mes');
            $table->decimal('saldo_pendiente', 10, 2)->default(0)->after('total_pagable');
            $table->string('periodo_mes_anio', 7)->nullable()->after('mes'); // Formato: 2025-08
            $table->text('observaciones')->nullable()->after('fecha_pago');
            
            // Indexar para mejorar búsquedas por fecha y trabajador
            $table->index(['trabajador_id', 'periodo_mes_anio']);
            $table->index(['fecha_pago']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sueldos', function (Blueprint $table) {
            $table->dropIndex(['trabajador_id', 'periodo_mes_anio']);
            $table->dropIndex(['fecha_pago']);
            $table->dropColumn(['tipo_pago', 'saldo_pendiente', 'periodo_mes_anio', 'observaciones']);
        });
    }
};