<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Recepcion;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cotizacion;
use App\Models\CotizacionRepuesto;
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

    public function store(Request $request, $id)
{
    // Validación de datos
    $validatedData = $request->validate([
        'equipos' => 'required|array',
        'equipos.*.descripcion' => 'required|string|max:1000',
        'equipos.*.valor_trabajo' => 'required|numeric|min:0',
        'equipos.*.repuestos_detalle' => 'nullable|array',
        'equipos.*.repuestos_detalle.*.nombre' => 'required|string|max:255',
        'equipos.*.repuestos_detalle.*.cantidad' => 'required|integer|min:1',
        'equipos.*.repuestos_detalle.*.precio' => 'required|numeric|min:0',
        'equipos.*.fotos' => 'nullable|array',
        'equipos.*.fotos.*' => 'integer|exists:fotos_equipos,id'
    ]);

    \Log::info('Request data:', $request->all());

    return DB::transaction(function () use ($request, $id, $validatedData) {
        // 1. Inicializa variables
        $subtotal = 0;
        $equiposData = $validatedData['equipos'];

        // 2. Crea o actualiza la cotización principal
        $cotizacion = Cotizacion::updateOrCreate(
            ['recepcion_id' => $id],
            [
                'fecha' => now(),
                'subtotal' => 0,
                'descuento' => $request->input('descuento', 0),
                'total' => 0
            ]
        );

        // 3. Elimina datos anteriores de manera segura
        if ($cotizacion->equipos()->exists()) {
            $cotizacion->equipos()->each(function ($equipo) {
                // Verifica si la relación existe antes de llamarla
                if (method_exists($equipo, 'repuestos')) {
                    $equipo->repuestos()->delete();
                }
                if (method_exists($equipo, 'fotos')) {
                    $equipo->fotos()->detach();
                }
            });
            $cotizacion->equipos()->delete();
        }

        // 4. Procesa cada equipo
        foreach ($equiposData as $equipoId => $data) {
            // Prepara datos
            $repuestos = $data['repuestos_detalle'] ?? [];
            $fotoIds = array_filter(array_map('intval', $data['fotos'] ?? []));
            $totalRepuestos = 0;

            // Crea el equipo en la cotización
            $cotizacionEquipo = $cotizacion->equipos()->create([
                'equipo_id' => $equipoId,
                'trabajo_realizar' => $data['descripcion'],
                'precio_trabajo' => $data['valor_trabajo'],
                'total_repuestos' => 0
            ]);

            // Procesa repuestos
            if (!empty($repuestos)) {
                $repuestosData = [];
                foreach ($repuestos as $rep) {
                    $subtotal = $rep['cantidad'] * $rep['precio'];
                    $totalRepuestos += $subtotal;
                    
                    $repuestosData[] = [
                        'cotizacion_equipo_id' => $cotizacionEquipo->id,
                        'nombre' => $rep['nombre'],
                        'cantidad' => $rep['cantidad'],
                        'precio_unitario' => $rep['precio'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                // Inserta todos los repuestos de una vez
                CotizacionRepuesto::insert($repuestosData);
                $cotizacionEquipo->total_repuestos = $totalRepuestos;
                $cotizacionEquipo->save();
            }

            // Asocia fotos
            if (!empty($fotoIds) && method_exists($cotizacionEquipo, 'fotos')) {
                $cotizacionEquipo->fotos()->sync($fotoIds);
            }

            // Suma al subtotal
            $subtotal += $data['valor_trabajo'] + $totalRepuestos;
        }

        // 5. Actualiza totales de la cotización
        $cotizacion->update([
            'subtotal' => $subtotal,
            'total' => $subtotal - $cotizacion->descuento
        ]);

        return redirect()
            ->route('cotizaciones.index')
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Cotización guardada',
                'text' => 'La cotización fue guardada correctamente.'
            ]);
    });
}    public function edit($id)
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

    public function show($id)
    {
        $recepcion = Recepcion::with(['cliente', 'equipos'])->findOrFail($id);

        // Aquí puedes retornar la vista de detalle de cotización
        return view('modules.cotizaciones.showCot', compact('recepcion'));
    }

    public function generarPdf($id)
    {
        $recepcion = Recepcion::with('cliente')->findOrFail($id);
        $pdf = Pdf::loadView('modules.cotizaciones.Generarpdf', compact('recepcion'));
        return $pdf->download('cotizacion_' . $recepcion->numero_recepcion . '.pdf');
    }
}
