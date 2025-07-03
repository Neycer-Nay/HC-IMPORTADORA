<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class UsuariosController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);
        return view('modules.usuarios.index', compact('users'));
    }

    public function create()
    {
        // Lógica para mostrar el formulario de creación de usuario
        return view('modules.usuarios.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|in:Gerente,Contabilidad,Supervisor',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'rol.required' => 'El rol es obligatorio.',
            'rol.in' => 'El rol seleccionado no es válido.'
        ]);

        try {
            $user = User::create([
                'nombre' => $validatedData['nombre'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'rol' => $validatedData['rol']
            ]);



            return redirect()->route('usuarios.index')
                ->with('swal', [
                    'icon' => 'success',
                    'title' => 'Usuario creado correctamente',
                    'text' => 'El usuario ha sido registrado exitosamente.'
                ]);
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('swal', [
                    'icon' => 'error',
                    'title' => 'Error al crear el usuario',
                    'text' => $e->getMessage()
                ]);
        }
    }
    public function show($usuario)
    {
        // Lógica para mostrar un usuario específico
    }

    public function edit($usuario)
    {
        // Lógica para mostrar el formulario de edición de un usuario
    }

    public function update(Request $request, $usuario)
    {
        // Lógica para actualizar un usuario existente
    }

    public function destroy(string $id)
    {
        try {
            // Buscar el usuario
            $user = User::findOrFail($id);

            // Opcional: Evitar que un usuario se elimine a sí mismo
            if (Auth::id() == $user->id) {
                return redirect()->route('usuarios.index')
                    ->with('swal', [
                        'icon' => 'error',
                        'title' => 'Operación no permitida',
                        'text' => 'No puedes eliminar tu propio usuario'
                    ]);
            }

            // Eliminar el usuario
            $user->delete();

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado correctamente');

        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->rol !== 'Gerente') {
                abort(403, 'Solo los usuarior con rol gerente pueden acceder a esta sección.');
            }
            return $next($request);
        });
    }
}
