<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recepcion;

class CotizacionController extends Controller
{
    public function index(Request $request)
    {   
        $query = Recepcion::with(['cliente', 'usuario'])->orderBy('created_at', 'desc');
        if ($request->filled('buscar')) {
            $busqueda = $request->buscar;
            $query->where(function ($q) use ($busqueda) {
                $q->where('numero_recepcion', 'like', "%$busqueda%")
                    ->orWhereHas('cliente', function ($qc) use ($busqueda) {
                        $qc->where('nombre', 'like', "%$busqueda%");
                    })
                    ->orWhereHas('usuario', function ($qu) use ($busqueda) {
                        $qu->where('nombre', 'like', "%$busqueda%");
                    });
            });
        }
        $recepciones = $query->paginate(10)->appends($request->only('buscar', 'cliente', 'usuario'));

        return view('modules.cotizaciones.indexCoti', [
            'recepciones' => $recepciones
        ]);
    }

    public function create()
    {
        // Aquí puedes implementar la lógica para mostrar el formulario de creación de cotización
        return view('modules.cotizaciones.createCoti', compact('recepciones'));
    }

    public function store(Request $request)
    {
        // Aquí puedes implementar la lógica para almacenar una nueva cotización
        // Validar los datos del formulario y guardarlos en la base de datos
    }
    public function edit($id)
    {
        $recepcion = Recepcion::with('cliente')->findOrFail($id);
        // Aquí puedes retornar la vista de edición de cotización
        return view('modules.cotizaciones.edit', compact('recepcion'));
    }
}
