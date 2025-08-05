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

        // Filtros aplicados
        $filtros = [
            'trabajador_id' => $request->trabajador_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'tipo_pago' => $request->tipo_pago,
            'nombre_trabajador' => $request->nombre_trabajador
        ];

        // Filtro por trabajador
        $filtroTrabajador = null;
        if ($request->has('trabajador_id') && !empty($request->trabajador_id)) {
            $query->where('trabajador_id', $request->trabajador_id);
            $filtroTrabajador = Trabajador::find($request->trabajador_id);
        }

        // Filtro por nombre de trabajador (búsqueda parcial)
        if ($request->has('nombre_trabajador') && !empty($request->nombre_trabajador)) {
            $query->whereHas('trabajador', function ($q) use ($request) {
                $q->where('nombre', 'LIKE', '%' . $request->nombre_trabajador . '%');
            });
        }

        // Filtro por rango de fechas
        if ($request->has('fecha_inicio') && !empty($request->fecha_inicio)) {
            $query->where('fecha_pago', '>=', $request->fecha_inicio);
        }

        if ($request->has('fecha_fin') && !empty($request->fecha_fin)) {
            $query->where('fecha_pago', '<=', $request->fecha_fin);
        }

        // Filtro por tipo de pago
        if ($request->has('tipo_pago') && !empty($request->tipo_pago)) {
            $query->where('tipo_pago', $request->tipo_pago);
        }

        $sueldos = $query->orderBy('fecha_pago', 'desc')->orderBy('created_at', 'desc')->get();

        // Calcular totales basados en los registros filtrados
        $totalSalarios = $sueldos->sum('salario');
        $totalAnticipos = $sueldos->where('tipo_pago', 'anticipo')->sum('total_pagable');
        $totalDescuentos = $sueldos->sum('descuentos');
        $totalHorasExtras = $sueldos->sum('horas_extras');
        $totalPagable = $sueldos->sum('total_pagable');

        // Obtener todos los trabajadores para el filtro
        $trabajadores = Trabajador::orderBy('nombre')->get();

        // Obtener períodos únicos para el filtro
        $periodos = Sueldo::distinct()
            ->whereNotNull('periodo_mes_anio')
            ->orderBy('periodo_mes_anio', 'desc')
            ->pluck('periodo_mes_anio');

        return view('contabilidad.sueldos.sueldos', compact(
            'sueldos',
            'trabajadores',
            'periodos',
            'filtroTrabajador',
            'filtros',
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
            'sueldo_base' => 'required|numeric|min:0',
            'fecha_ingreso' => 'nullable|date',
        ]);

        Trabajador::create([
            'nombre' => $request->nombre,
            'cargo' => $request->cargo,
            'sueldo_base' => $request->sueldo_base,
            'fecha_ingreso' => $request->fecha_ingreso ?? now(),
            'activo' => true,
        ]);

        return redirect()->route('sueldos.index')->with('success', 'Trabajador agregado exitosamente');
    }

    public function storePago(Request $request)
    {
        $request->validate([
            'trabajador_id' => 'required|exists:trabajadores,id',
            'mes' => 'required|string',
            'tipo_pago' => 'required|in:salario_completo,anticipo,pago_final',
            'salario' => 'nullable|numeric|min:0',
            'anticipos' => 'nullable|numeric|min:0',
            'descuentos' => 'nullable|numeric|min:0',
            'horas_extras' => 'nullable|numeric|min:0',
            'fecha_pago' => 'required|date',
            'observaciones' => 'nullable|string|max:500',
        ]);

        // Obtener el trabajador para usar su sueldo base
        $trabajador = Trabajador::findOrFail($request->trabajador_id);

        // Generar período automáticamente
        $periodo = date('Y-m', strtotime($request->fecha_pago));

        // Validaciones específicas por tipo de pago
        if ($request->tipo_pago == 'anticipo') {
            if (empty($request->anticipos) || $request->anticipos <= 0) {
                return back()->withErrors(['anticipos' => 'Debe especificar un monto de anticipo mayor a 0'])->withInput();
            }

            // Verificar que el anticipo no exceda el 80% del sueldo base
            $maxAnticipo = $trabajador->sueldo_base * 0.8;
            $anticiposYaDados = Sueldo::where('trabajador_id', $request->trabajador_id)
                ->where('periodo_mes_anio', $periodo)
                ->where('tipo_pago', 'anticipo')
                ->sum('total_pagable');

            if (($anticiposYaDados + $request->anticipos) > $maxAnticipo) {
                return back()->withErrors(['anticipos' => 'El anticipo excede el 80% del sueldo base mensual'])->withInput();
            }
        }

        if ($request->tipo_pago == 'pago_final') {
            // Verificar que existan anticipos previos en el período
            $anticiposPrevios = Sueldo::where('trabajador_id', $request->trabajador_id)
                ->where('periodo_mes_anio', $periodo)
                ->where('tipo_pago', 'anticipo')
                ->sum('total_pagable');

            if ($anticiposPrevios == 0) {
                return back()->withErrors(['tipo_pago' => 'No hay anticipos registrados para este período'])->withInput();
            }
        }

        $sueldo = new Sueldo();
        $sueldo->trabajador_id = $request->trabajador_id;
        $sueldo->mes = $request->mes;
        $sueldo->periodo_mes_anio = $periodo;
        $sueldo->tipo_pago = $request->tipo_pago;

        // Usar sueldo base del trabajador si no se especifica uno diferente
        $sueldo->salario = $request->salario ?? $trabajador->sueldo_base;
        $sueldo->anticipos = $request->anticipos ?? 0;
        $sueldo->descuentos = $request->descuentos ?? 0;
        $sueldo->horas_extras = $request->horas_extras ?? 0;
        $sueldo->fecha_pago = $request->fecha_pago;
        $sueldo->observaciones = $request->observaciones;

        // El total_pagable y saldo_pendiente se calculan automáticamente en el modelo
        $sueldo->save();

        $mensaje = 'Pago registrado exitosamente';
        if ($request->tipo_pago == 'anticipo') {
            $mensaje = 'Anticipo registrado exitosamente';
        } elseif ($request->tipo_pago == 'pago_final') {
            $mensaje = 'Pago final registrado exitosamente';
        }

        return redirect()->route('sueldos.index')->with('success', $mensaje);
    }

    public function getTrabajadores()
    {
        $trabajadores = Trabajador::all();
        return response()->json($trabajadores);
    }

    public function destroy($id)
    {
        $sueldo = Sueldo::findOrFail($id);
        $sueldo->delete();

        // Los totales se recalculan automáticamente en el método index al recargar la vista
        return redirect()->route('sueldos.index')->with('success', 'Registro de sueldo eliminado correctamente');
    }
}
