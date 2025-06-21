<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        
        $clientes = Cliente::latest()->paginate(10);
        return view('modules.recepciones.create', compact('clientes'));
    }

   
    public function create()
    {
        $clientes = Cliente::all();
        return view('modules.recepciones.create', compact('clientes'));
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:PERSONA,EMPRESA',
            'tipo_documento' => 'required|in:CI,NIT,PASAPORTE,OTRO',
            'numero_documento' => 'required|string|unique:clientes,numero_documento|max:50',
            'telefono_1' => 'required|string|max:20',
            'telefono_2' => 'nullable|string|max:20',
            'telefono_3' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'ciudad' => 'nullable|string|max:100',
            'direccion' => 'required|string|max:255',
            ],
            [
                'nombre.required' => 'El nombre es obligatorio.',
                'tipo.required' => 'El tipo de cliente es obligatorio.',
                'tipo_documento.required' => 'El tipo de documento esta dublicado.',
                'numero_documento.unique' => 'Este número de documento ya está registrado para otro cliente.',
                'telefono_1.required' => 'El teléfono 1 es obligatorio.',
                'direccion.required' => 'La dirección es obligatoria.',
            ]);

            Cliente::create($validated);

            return redirect()->back()
                ->with('success', 'Cliente registrado correctamente.');


    }

    public function show(Cliente $cliente)
    {
        
        $clientes = Cliente::all();
        return response()->json($clientes);
    }

}
