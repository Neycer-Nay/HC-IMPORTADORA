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
        'periodo_mes_anio',
        'tipo_pago',
        'salario',
        'anticipos',
        'descuentos',
        'horas_extras',
        'total_pagable',
        'saldo_pendiente',
        'fecha_pago',
        'observaciones'
    ];

    protected $casts = [
        'salario' => 'decimal:2',
        'anticipos' => 'decimal:2',
        'descuentos' => 'decimal:2',
        'horas_extras' => 'decimal:2',
        'total_pagable' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
        'fecha_pago' => 'date'
    ];

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class);
    }

    // Calcular el total pagable automáticamente según el tipo de pago
    public function calcularTotalPagable()
    {
        $salario = (float) $this->salario;
        $anticipos = (float) $this->anticipos;
        $descuentos = (float) $this->descuentos;
        $horas_extras = (float) $this->horas_extras;
        
        switch ($this->tipo_pago) {
            case 'anticipo':
                // Para anticipos, el total pagable es solo el monto del anticipo
                $this->total_pagable = $anticipos;
                $this->saldo_pendiente = $salario + $horas_extras - $anticipos - $descuentos;
                break;
                
            case 'pago_final':
                // Para pago final, descontamos los anticipos ya dados
                $totalAnticiposPrevios = $this->obtenerAnticiposPeriodo();
                $this->total_pagable = $salario + $horas_extras - $totalAnticiposPrevios - $descuentos;
                $this->saldo_pendiente = 0;
                break;
                
            default: // 'salario_completo'
                // Pago completo del mes
                $this->total_pagable = $salario + $horas_extras - $anticipos - $descuentos;
                $this->saldo_pendiente = 0;
                break;
        }
        
        return $this->total_pagable;
    }

    // Obtener total de anticipos dados en el mismo período
    public function obtenerAnticiposPeriodo()
    {
        if (!$this->periodo_mes_anio || !$this->trabajador_id) {
            return 0;
        }
        
        return static::where('trabajador_id', $this->trabajador_id)
            ->where('periodo_mes_anio', $this->periodo_mes_anio)
            ->where('tipo_pago', 'anticipo')
            ->where('id', '!=', $this->id ?? 0)
            ->sum('total_pagable');
    }

    // Obtener saldo pendiente del período
    public function getSaldoPendientePeriodo()
    {
        if (!$this->periodo_mes_anio || !$this->trabajador_id) {
            return 0;
        }

        $salarioMensual = static::where('trabajador_id', $this->trabajador_id)
            ->where('periodo_mes_anio', $this->periodo_mes_anio)
            ->where('tipo_pago', '!=', 'anticipo')
            ->first();

        if (!$salarioMensual) {
            return 0;
        }

        $totalAnticipos = $this->obtenerAnticiposPeriodo();
        $salarioBase = $salarioMensual->salario + $salarioMensual->horas_extras;
        
        return $salarioBase - $totalAnticipos - $salarioMensual->descuentos;
    }

    // Scopes para filtrado
    public function scopePorTrabajador($query, $trabajadorId)
    {
        return $query->where('trabajador_id', $trabajadorId);
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_pago', [$fechaInicio, $fechaFin]);
    }

    public function scopePorPeriodo($query, $periodo)
    {
        return $query->where('periodo_mes_anio', $periodo);
    }

    public function scopePorTipoPago($query, $tipo)
    {
        return $query->where('tipo_pago', $tipo);
    }

    // Boot method para calcular automáticamente el total
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($sueldo) {
            // Generar período automáticamente si no existe
            if (!$sueldo->periodo_mes_anio && $sueldo->fecha_pago) {
                $sueldo->periodo_mes_anio = date('Y-m', strtotime($sueldo->fecha_pago));
            }
            
            $sueldo->calcularTotalPagable();
        });
    }
}