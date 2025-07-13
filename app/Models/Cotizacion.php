<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'recepcion_id', // <-- agrega esto
        'fecha',
        'subtotal',
        'descuento',
        'total'
    ];

    public function recepcion()
    {
        return $this->belongsTo(Recepcion::class);
    }
    public function equipos()
    {
        return $this->hasMany(CotizacionEquipo::class);
    }

    // Método para recalcular totales
    public function recalcularTotales()
    {
        $subtotal = 0;
    
    foreach ($this->equipos as $equipo) {
        // Calcular total de repuestos para este equipo
        $totalRepuestos = $equipo->repuestos->sum(function($repuesto) {
            return $repuesto->cantidad * $repuesto->precio_unitario;
        });
        
        $equipo->total_repuestos = $totalRepuestos;
        $equipo->save();
        
        $subtotal += $equipo->precio_trabajo + $totalRepuestos;
    }
    
    $this->subtotal = $subtotal;
    $this->total = $subtotal - $this->descuento;
    $this->save();
    
    return $this;
    }
}
