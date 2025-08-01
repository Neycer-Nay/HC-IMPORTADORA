<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IngresosController extends Controller
{
    public function index()
    {
         $ingresos = \App\Models\Ingreso::orderBy('created_at', 'desc')->get();
        return view('contabilidad.ingresos.ingresos', compact('ingresos'));
    }

    public function create()
    {
        // Lógica para mostrar el formulario de creación de ingresos
        return view('contabilidad.ingresos.ingresos');
    }

    public function store(Request $request)
    {
        // Lógica para almacenar un nuevo ingreso
        $data = $request->validate([
            'tipo_ingreso' => 'required|string|max:255',
            'glosa' => 'required|string|max:255',
            'razon_social' => 'required|string|max:255',
            'nro_recibo' => 'required|string|max:255',
            'metodo_pago' => 'required|string|max:255',
            'subtotal' => 'required|numeric',
            'descuento' => 'nullable|numeric',
            
            'estado_pago' => 'required|string|max:255',
        ]);
        $data['total'] = $data['subtotal'] - ($data['descuento'] ?? 0);
        \App\Models\Ingreso::create($data);

        return redirect()->route('ingresos.index')->with('success', 'Ingreso creado exitosamente.');
    }

    public function edit($id)
    {
        // Lógica para mostrar el formulario de edición de un ingreso
        $ingreso = \App\Models\Ingreso::findOrFail($id);
        return view('contabilidad.ingresos.edit', compact('ingreso'));
    }

    public function update(Request $request, $id)
    {
        // Lógica para actualizar un ingreso existente
        $data = $request->validate([
            'tipo_ingreso' => 'required|string|max:255',
            'glosa' => 'required|string|max:255',
            'razon_social' => 'required|string|max:255',
            'nro_recibo' => 'required|string|max:255',
            'metodo_pago' => 'required|string|max:255',
            'subtotal' => 'required|numeric',
            'descuento' => 'nullable|numeric',
            
            'estado_pago' => 'required|string|max:255',
        ]);
        $data['total'] = $data['subtotal'] - ($data['descuento'] ?? 0);
        $ingreso = \App\Models\Ingreso::findOrFail($id);
        $ingreso->update($data);

        return redirect()->route('ingresos.index')->with('success', 'Ingreso actualizado exitosamente.');
    }

    public function destroy($id)
    {
        // Lógica para eliminar un ingreso
        $ingreso = \App\Models\Ingreso::findOrFail($id);
        $ingreso->delete();

        return redirect()->route('ingresos.index')->with('success', 'Ingreso eliminado exitosamente.');
    }

    public function show($id)
    {
        // Lógica para mostrar los detalles de un ingreso
        $ingreso = \App\Models\Ingreso::findOrFail($id);
        return view('contabilidad.ingresos.ingreso', compact('ingreso'));
    } 
}
