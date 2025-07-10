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
}
