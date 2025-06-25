<?php

namespace App\Http\Controllers;

use App\Models\Recepcion;
use App\Models\Cliente;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Log;

class RecepcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recepciones = Recepcion::with(['cliente', 'usuario'])
        ->orderBy('created_at', 'desc')
        ->get();
        return view('modules.recepciones.index', [
        'recepciones' => $recepciones]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $ultimaRecepcion = Recepcion::latest()->first();
        $numeroRecepcion = $ultimaRecepcion ? 'REC-' . (str_pad((int) Str::after($ultimaRecepcion->numero_recepcion, 'REC-') + 1, 5, '0', STR_PAD_LEFT)) : 'REC-5555';

        $usuario = Auth::user();
        $clientes = Cliente::all();

        return view('modules.recepciones.create', compact('clientes', 'numeroRecepcion'));
    }

    public function store(Request $request)
    {
        

        // Validación de datos
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'numero_recepcion' => 'required|unique:recepciones',
            'fecha_ingreso' => 'required|date', // Cambiado a fecha_ingreso para coincidir con BD
            'hora_ingreso' => 'required',
            'observaciones' => 'nullable|string',
            'equipos' => 'required|array|min:1',
            'equipos.*.tipo' => 'required|in:MOTOR_ELECTRICO,MAQUINA_SOLDADORA,GENERADOR_DINAMO,OTROS',
            'equipos.*.marca' => 'required|string|max:255',
            'equipos.*.nombre' => 'required|string|max:255',
        ]);

        // Crear la recepción
        $recepcion = Recepcion::create([
            'numero_recepcion' => $request->numero_recepcion,
            'cliente_id' => $request->cliente_id,
            'user_id' => Auth::id(),
            'fecha_ingreso' => $request->fecha_ingreso, // Asegúrate que coincide con el name del input
            'hora_ingreso' => $request->hora_ingreso,
            'observaciones' => $request->observaciones,
            'estado' => 'RECIBIDO'
        ]);

        // Guardar los equipos
        foreach ($request->equipos as $equipoData) {
            $equipo = new Equipo([
                'recepcion_id' => $recepcion->id,
                'cliente_id' => $request->cliente_id,
                'nombre' => $equipoData['nombre'],
                'tipo' => $equipoData['tipo'],
                'marca' => $equipoData['marca'],
                'numero_serie' => $equipoData['serie'] ?? null,
                'color' => isset($equipoData['color']) ? implode(',', (array) $equipoData['color']) : null,
                // ... resto de campos
            ]);

            $equipo->save();
        }

        return redirect()->route('recepciones.index')
        ->with('success', 'Recepción registrada correctamente'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Recepcion $recepcion)
{
    // Carga las relaciones (esto se puede hacer directamente en el modelo con $with si siempre las necesitas)
    $recepcion->load(['cliente', 'usuario', 'equipos']);
    
    // Retorna la vista de DETALLE (show) con los datos
    return view('modules.recepciones.show', ['recepcion' => $recepcion]);
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recepcion $recepcion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recepcion $recepcion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recepcion $recepcion)
    {
        //
    }
}
