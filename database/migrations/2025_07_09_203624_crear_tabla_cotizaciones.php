<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla principal de cotizaciones
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recepcion_id')->constrained('recepciones');
            $table->date('fecha')->useCurrent();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamps();
        });

        // Tabla detalle por equipo
        Schema::create('cotizacion_equipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->onDelete('cascade');
            $table->foreignId('equipo_id')->constrained('equipos');
            $table->text('trabajo_realizar')->nullable();
            $table->decimal('precio_trabajo', 12, 2)->default(0);

            // Puedes guardar los repuestos como JSON para flexibilidad
            $table->json('repuestos')->nullable(); // [{nombre, cantidad, precio_unitario}]
            $table->decimal('total_repuestos', 12, 2)->default(0);

            $table->json('fotos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizacion_equipos');
        Schema::dropIfExists('cotizaciones');
    }
};
