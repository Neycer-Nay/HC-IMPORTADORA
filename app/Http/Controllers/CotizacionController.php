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
        // Verificar si ya existe una cotización para esta recepción
        $cotizacionExistente = Cotizacion::where('recepcion_id', $id)->first();

        if ($cotizacionExistente) {
            return redirect()->route('recepciones.index')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Cotización ya existe',
                    'text' => 'Esta recepción ya tiene una cotización creada.'
                ]);
        }

        // Validación de datos
        $validatedData = $request->validate([
            'equipos' => 'required|array',
            'equipos.*.equipo_id' => 'required|exists:equipos,id',
            'equipos.*.descripcion' => 'required|string|max:1000',
            'equipos.*.valor_trabajo' => 'required|numeric|min:0',
            'equipos.*.repuestos_detalle' => 'nullable|array',
            'equipos.*.repuestos_detalle.*.nombre' => 'required|string|max:255',
            'equipos.*.repuestos_detalle.*.cantidad' => 'required|integer|min:1',
            'equipos.*.repuestos_detalle.*.precio' => 'required|numeric|min:0',
            'equipos.*.fotos' => 'nullable|array',
            'equipos.*.fotos.*' => 'integer|exists:fotos_equipos,id',
            'descuento' => 'nullable|numeric|min:0'
        ]);

        return DB::transaction(function () use ($request, $id, $validatedData) {
            // Crear la cotización (cambiar updateOrCreate por create)
            $cotizacion = Cotizacion::create([
                'recepcion_id' => $id,
                'fecha' => now(),
                'subtotal' => 0,
                'descuento' => $validatedData['descuento'] ?? 0,
                'total' => 0
            ]);

            $subtotalGeneral = 0;

            // Procesa cada equipo
            foreach ($validatedData['equipos'] as $equipoData) {
                $totalRepuestos = 0;
                $repuestosData = [];

                // Procesa repuestos si existen
                if (!empty($equipoData['repuestos_detalle'])) {
                    foreach ($equipoData['repuestos_detalle'] as $repuesto) {
                        $subtotalRepuesto = $repuesto['cantidad'] * $repuesto['precio'];
                        $totalRepuestos += $subtotalRepuesto;

                        $repuestosData[] = new CotizacionRepuesto([
                            'nombre' => $repuesto['nombre'],
                            'cantidad' => $repuesto['cantidad'],
                            'precio_unitario' => $repuesto['precio']
                        ]);
                    }
                }

                // Crea el equipo en la cotización
                $cotizacionEquipo = $cotizacion->equipos()->create([
                    'equipo_id' => $equipoData['equipo_id'],
                    'trabajo_realizar' => $equipoData['descripcion'],
                    'precio_trabajo' => $equipoData['valor_trabajo'],
                    'total_repuestos' => $totalRepuestos
                ]);

                // Asocia repuestos
                if (!empty($repuestosData)) {
                    $cotizacionEquipo->repuestos()->saveMany($repuestosData);
                }

                // Asocia fotos si existen
                if (!empty($equipoData['fotos'])) {
                    $cotizacionEquipo->fotos()->sync($equipoData['fotos']);
                }

                // Suma al subtotal general
                $subtotalGeneral += $equipoData['valor_trabajo'] + $totalRepuestos;
            }

            // Actualiza totales de la cotización
            $cotizacion->update([
                'subtotal' => $subtotalGeneral,
                'total' => $subtotalGeneral - $cotizacion->descuento
            ]);

            return redirect()
                ->route('cotizaciones.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => 'Cotización creada',
                    'text' => 'La cotización fue creada correctamente.'
                ]);
        });
    }
    public function edit($id)
    {
        // Verificar si ya existe una cotización para esta recepción
        $cotizacionExistente = Cotizacion::where('recepcion_id', $id)->first();

        if ($cotizacionExistente) {
            return redirect()->route('recepciones.index')
                ->with('swal', [
                    'icon' => 'warning',
                    'title' => 'Cotización ya existe',
                    'text' => 'Esta recepción ya tiene una cotización creada. No se puede modificar.'
                ]);
        }

        $recepcion = Recepcion::with(['cliente', 'equipos.fotos'])->findOrFail($id);
        return view('modules.cotizaciones.editCoti', compact('recepcion'));
    }


    public function update(Request $request, $id)
    {
        return redirect()->route('cotizaciones.index')
            ->with('swal', [
                'icon' => 'error',
                'title' => 'Acción no permitida',
                'text' => 'No se pueden actualizar las cotizaciones.'
            ]);
    }

    public function show($id)
    {
        $cotizacion = Cotizacion::with([
            'recepcion.cliente',
            'equipos' => function ($query) {
                $query->with([
                    'equipo.fotos', // Todas las fotos del equipo
                    'fotos',        // Fotos seleccionadas para la cotización
                    'repuestos'     // Repuestos de la cotización
                ]);
            }
        ])->where('recepcion_id', $id)->first();

        if (!$cotizacion) {
            return redirect()->route('cotizaciones.index')
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'No se encontró la cotización para esta recepción'
                ]);
        }

        return view('modules.cotizaciones.showCot', compact('cotizacion'));
    }

    public function generarPdf($id)
    {
        // Buscar la cotización con todas las relaciones necesarias
        $cotizacion = Cotizacion::with([
            'recepcion.cliente',
            'equipos' => function ($query) {
                $query->with([
                    'equipo', // Datos del equipo
                    'fotos',  // Fotos seleccionadas para la cotización
                    'repuestos' // Repuestos de la cotización
                ]);
            }
        ])->where('recepcion_id', $id)->first();

        if (!$cotizacion) {
            return redirect()->route('cotizaciones.index')
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error',
                    'text' => 'No se encontró la cotización para esta recepción'
                ]);
        }

        // Datos para el PDF
        $data = [
            'cotizacion' => $cotizacion,
            'recepcion' => $cotizacion->recepcion,
            'cliente' => $cotizacion->recepcion->cliente,
            'subtotal' => $cotizacion->subtotal,
            'descuento' => $cotizacion->descuento,
            'total' => $cotizacion->total
        ];

        $pdf = Pdf::loadView('modules.cotizaciones.Generarpdf', $data);
        return $pdf->download('cotizacion_' . $cotizacion->recepcion->numero_recepcion . '.pdf');
    }
}
