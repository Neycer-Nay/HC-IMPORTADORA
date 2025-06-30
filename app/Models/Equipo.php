<?php

namespace App\Models;

use Illuminate\Cache\Events\ForgettingKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'recepcion_id',
        'cliente_id',
        'nombre',
        'tipo',
        'marca',
        'modelo',
        'color',
        'numero_serie',
        'potencia',
        'voltaje',
        'hp',
        'rpm',
        'hz',
        'amperaje',
        'cable_positivo',
        'cable_negativo',
        'kva_kw',
        'partes_faltantes',
        'observaciones',
    ];

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con Recepción
    public function recepcion()
    {
        return $this->belongsTo(Recepcion::class);
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(FotoEquipo::class, 'equipo_id');
    }
}
