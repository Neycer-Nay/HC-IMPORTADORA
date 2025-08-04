<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;

    protected $table = 'trabajadores';

    protected $fillable = [
        'nombre',
        'cargo',
        'sueldo_base',
        'activo',
        'fecha_ingreso'
    ];

    protected $casts = [
        'sueldo_base' => 'decimal:2',
        'activo' => 'boolean',
        'fecha_ingreso' => 'date'
    ];

    public function sueldos()
    {
        return $this->hasMany(Sueldo::class);
    }
}