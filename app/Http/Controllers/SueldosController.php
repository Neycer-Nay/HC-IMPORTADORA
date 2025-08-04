<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabajador;
use App\Models\Sueldo;
use Illuminate\Support\Facades\DB;

class SueldosController extends Controller
{
    public function index(Request $request)
    {
        $query = Sueldo::with('trabajador');
        
        // Filtro por trabajador si se proporciona
        $filtroTrabajador = null;
        if ($request->has('trabajador_id') && !empty($request->trabajador_id)) {
            $query->where('trabajador_id', $request->trabajador_id);
            $filtroTrabajador = Trabajador::find($request->trabajador_id);
        }

        $sueldos = $query->orderBy('created_at', 'desc')->get();
        
        // Calcular totales
        $totalSalarios = $sueldos->sum('salario');
        $totalAnticipos = $sueldos->sum('anticipos');
        $totalDescuentos = $sueldos->sum('descuentos');
        $totalHorasExtras = $sueldos->sum('horas_extras');
        $totalPagable = $sueldos->sum('total_pagable');
        
        // Obtener todos los trabajadores para el filtro
        $trabajadores = Trabajador::orderBy('nombre')->get();
        
        return view('contabilidad.sueldos.sueldos', compact(
            'sueldos', 
            'trabajadores',
            'filtroTrabajador',
            'totalSalarios',
            'totalAnticipos', 
            'totalDescuentos',
            'totalHorasExtras',
            'totalPagable'
        ));
    }

    public function storeTrabajador(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cargo' => 'required|string|max:255',
        ]);

        Trabajador::create([
            'nombre' => $request->nombre,
            'cargo' => $request->cargo,
        ]);

        return redirect()->route('sueldos.index')->with('success', 'Trabajador agregado exitosamente');
    }

    public function storePago(Request $request)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id',
            'mes' => 'required|string',
            'salario' => 'required|numeric|min:0',
            'anticipos' => 'nullable|numeric|min:0',
            'descuentos' => 'nullable|numeric|min:0',
            'horas_extras' => 'nullable|numeric|min:0',
            'fecha_pago' => 'required|date',
        ]);

        $sueldo = new Sueldo();
        $sueldo->trabajador_id = $request->trabajador_id;
        $sueldo->mes = $request->mes;
        $sueldo->salario = $request->salario ?? 0;
        $sueldo->anticipos = $request->anticipos ?? 0;
        $sueldo->descuentos = $request->descuentos ?? 0;
        $sueldo->horas_extras = $request->horas_extras ?? 0;
        $sueldo->fecha_pago = $request->fecha_pago;
        
        // El total_pagable se calcula automáticamente en el modelo
        $sueldo->save();

        return redirect()->route('sueldos.index')->with('success', 'Pago registrado exitosamente');
    }

    public function getTrabajadores()
    {
        $trabajadores = Trabajador::all();
        return response()->json($trabajadores);
    }
}
