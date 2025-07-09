<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recepcion;
use Barryvdh\DomPDF\Facade\Pdf;

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
        return view('modules.cotizaciones.editCoti', compact('recepcion'));
    }

    public function update(Request $request, $id)
    {
        $recepcion = Recepcion::findOrFail($id);
        // Valida y actualiza los campos necesarios
        $recepcion->update($request->all());
        return redirect()->route('cotizaciones.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Actualizado',
            'text' => 'La información de la recepción fue actualizada correctamente.'
        ]);
    }

    public function generarPdf($id)
    {
        $recepcion = Recepcion::with('cliente')->findOrFail($id);
        $pdf = Pdf::loadView('modules.cotizaciones.Generarpdf', compact('recepcion'));
        return $pdf->download('cotizacion_' . $recepcion->numero_recepcion . '.pdf');
    }
}
