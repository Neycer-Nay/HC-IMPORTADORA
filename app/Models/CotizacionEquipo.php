<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CotizacionEquipo extends Model
{
    protected $fillable = [
        'recepcion_id',
        'cotizacion_id',
        'equipo_id',
        'trabajo_realizar',
        'precio_trabajo',
        'repuestos',
        'total_repuestos',
        'fotos', // <-- agrega esto
        
    ];
    protected $casts = [
    'repuestos' => 'array',
    'fotos' => 'array',
    ];
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }
}
