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
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->decimal('sueldo_base', 10, 2)->default(0)->after('cargo');
            $table->boolean('activo')->default(true)->after('sueldo_base');
            $table->date('fecha_ingreso')->nullable()->after('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->dropColumn(['sueldo_base', 'activo', 'fecha_ingreso']);
        });
    }
};