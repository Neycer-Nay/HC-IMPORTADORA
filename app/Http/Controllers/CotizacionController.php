<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recepcion;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cotizacion;
use App\Models\CotizacionEquipo;

class CotizacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Cotizacion::with(['recepcion.cliente', 'recepcion.usuario'])
                ->orderBy('created_at', 'desc');

    if ($request->filled('buscar')) {
        $busqueda = $request->buscar;
        $query->where(function ($q) use ($busqueda) {
            $q->where('id', 'like', "%$busqueda%")
                ->orWhereHas('recepcion', function ($qr) use ($busqueda) {
                    $qr->where('numero_recepcion', 'like', "%$busqueda%")
                       ->orWhereHas('cliente', function ($qc) use ($busqueda) {
                           $qc->where('nombre', 'like', "%$busqueda%");
                       });
                });
        });
    }

    $cotizaciones = $query->paginate(10)->appends($request->only('buscar'));

    return view('modules.cotizaciones.indexCoti', [
        'cotizaciones' => $cotizaciones
    ]);
    }

    public function create()
    {
        // Aquí puedes implementar la lógica para mostrar el formulario de creación de cotización
        return view('modules.cotizaciones.createCoti', compact('recepciones'));
    }

    public function store(Request $request,$id)
    {
        // 1. Calcula totales
        $subtotal = 0;
        $equiposData = $request->input('equipos', []);

        // 2. Crea o actualiza la cotización
        $cotizacion = Cotizacion::updateOrCreate(
            ['recepcion_id' => $id],
            [
                'fecha' => now(),
                'subtotal' => 0, // temporal, se actualiza abajo
                'descuento' => 0,
                'total' => 0
            ]
        );

        // 3. Limpia detalles anteriores si es update
        $cotizacion->equipos()->delete();

        // 4. Guarda cada equipo
        foreach ($equiposData as $equipoId => $data) {
            $repuestos = $data['repuestos_detalle'] ?? [];
            $fotos = $data['fotos'] ?? []; // <-- Agrega esto
            $totalRepuestos = 0;
            foreach ($repuestos as $rep) {
                $totalRepuestos += (float) ($rep['cantidad'] ?? 0) * (float) ($rep['precio'] ?? 0);
            }
            $subtotal += (float) ($data['valor_trabajo'] ?? 0) + $totalRepuestos;

            CotizacionEquipo::create([
                'cotizacion_id' => $cotizacion->id,
                'equipo_id' => $equipoId,
                'trabajo_realizar' => $data['descripcion'] ?? '',
                'precio_trabajo' => $data['valor_trabajo'] ?? 0,
                'repuestos' => json_encode($repuestos),
                'total_repuestos' => $totalRepuestos,
                'fotos' => json_encode($fotos), // <-- Guarda las fotos seleccionadas
            ]);
        }


        // 5. Actualiza totales
        $cotizacion->subtotal = $subtotal;
        $cotizacion->total = $subtotal - $cotizacion->descuento;
        $cotizacion->save();

        return redirect()->route('cotizaciones.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Cotización guardada',
            'text' => 'La cotización fue guardada correctamente.'
        ]);
    }
    public function edit($id)
    {
        $recepcion = Recepcion::with(['cliente', 'equipos'])->findOrFail($id);
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
