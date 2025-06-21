<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Recepcion;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo',
        'tipo_documento',
        'numero_documento',
        'telefono_1',
        'telefono_2',
        'telefono_3',
        'email',
        'ciudad',
        'direccion',
    ];

    public function recepciones()
    {
        return $this->hasMany(Recepcion::class);
    }

}
