<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UsuarioRequest;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Cita;
use App\Models\Producto;
use App\Models\Servicio;

class UsuarioController extends Controller
{
    // Listar usuarios
    public function index()
    {
        $usuarios = Usuario::orderBy('id', 'asc')->paginate(10);
        return Inertia::render('Usuarios/Index', [
        'usuarios' => $usuarios,
        ]);
    }

    public function dashboard()
    {
    return Inertia::render('Usuarios/Dashboard', [
        'estadisticas' => [
            'pacientes' => Paciente::count(),
            'medicos' => Medico::count(),
            'citas' => Cita::count(),
            'productos' => Producto::count(),
            'servicios' => Servicio::count(),
        ]
    ]);
    }

    // Mostrar formulario de creación
    public function create()
    {
        return Inertia::render('Usuarios/Create');
    }

    // Guardar nuevo usuario
    public function store(UsuarioRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = \Hash::make($request->password);
        Usuario::create($validated);
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    // Mostrar formulario de edición
    public function edit(Usuario $usuario)
    {
        return Inertia::render('Usuarios/Edit', [
            'usuario' => $usuario,
        ]);
    }

    // Actualizar usuario
    public function update(UsuarioRequest $request, Usuario $usuario)
    {
        $validated = $request->validated();
        if ($request->filled('password')) {
            $validated['password'] = \Hash::make($request->password);
        } else {
            unset($validated['password']);
        }
        $usuario->update($validated);
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // Eliminar usuario
    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
