<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EgresosController extends Controller
{
    public function index()
    {
        $cuentas = \App\Models\NombreCuenta::all();
        $egresos = \App\Models\Egreso::orderBy('created_at', 'desc')->paginate(10);
        return view('contabilidad.egresos.egresos', compact('cuentas', 'egresos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cuenta_id' => 'required|exists:nombre_cuentas,id',
            'glosa' => 'required|string|max:255',
            'razon_social' => 'required|string|max:255',
            'nro_factura' => 'required|string|max:255',
            'responsable' => 'required|string|max:255',
            'metodo_pago' => 'required|in:Efectivo,Banco,Por pagar',
            'subtotal' => 'required|numeric|min:0',
            'descuento' => 'nullable|numeric|min:0',
            
        ]);

        $data['total'] = $data['subtotal'] - ($data['descuento'] ?? 0);

        \App\Models\Egreso::create($data);

        return redirect()->route('egresos.index')->with('success', 'Egreso registrado correctamente.');
    }

    public function storeCuenta(Request $request)
    {
        $data = $request->validate([
            'nombre_cuenta' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
        ]);

        \App\Models\NombreCuenta::create($data);

        return redirect()->route('egresos.index')->with('success', 'Cuenta agregada correctamente.');
    }


}
