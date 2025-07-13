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
    
    public function cotizacion()
    {
        return $this->belongsTo(Cotizacion::class);
    }
    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

public function repuestos()
    {
        return $this->hasMany(CotizacionRepuesto::class, 'cotizacion_equipo_id');
    }


    public function fotos()
    {
        return $this->belongsToMany(FotoEquipo::class, 'cotizacion_equipo_fotos', 'cotizacion_equipo_id', 'foto_equipo_id');
    }
    
    
    // Método para calcular el total de repuestos automáticamente
    
    
}
