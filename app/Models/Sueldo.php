<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sueldo extends Model
{
    use HasFactory;

    protected $table = 'sueldos';

    protected $fillable = [
        'trabajador_id',
        'mes',
        'salario',
        'anticipos',
        'descuentos',
        'horas_extras',
        'total_pagable',
        'fecha_pago'
    ];

    protected $casts = [
        'salario' => 'decimal:2',
        'anticipos' => 'decimal:2',
        'descuentos' => 'decimal:2',
        'horas_extras' => 'decimal:2',
        'total_pagable' => 'decimal:2',
        'fecha_pago' => 'date'
    ];

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class);
    }

    // Calcular el total pagable automáticamente
    public function calcularTotalPagable()
    {
        $salario = (float) $this->salario;
        $anticipos = (float) $this->anticipos;
        $descuentos = (float) $this->descuentos;
        $horas_extras = (float) $this->horas_extras;
        
        $this->total_pagable = $salario + $horas_extras - $anticipos - $descuentos;
        return $this->total_pagable;
    }

    // Boot method para calcular automáticamente el total
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($sueldo) {
            $sueldo->calcularTotalPagable();
        });
    }
}