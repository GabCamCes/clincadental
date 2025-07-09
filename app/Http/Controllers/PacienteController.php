<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Usuario;
use App\Http\Requests\PacienteRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class PacienteController extends Controller
{
    public function index()
    {
        // Lista todos los pacientes con su usuario relacionado
        $pacientes = Paciente::with('usuario')->orderByDesc('id')->paginate(10);
        return Inertia::render('Pacientes/Index', [
            'pacientes' => $pacientes,
        ]);
    }

    public function create()
    {
        return Inertia::render('Pacientes/Create');
    }

    public function store(PacienteRequest $request)
    {
        // Crear usuario tipo P
        $usuario = Usuario::create([
            'ci' => $request->ci,
            'nombres' => $request->nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'edad' => $request->edad,
            'genero' => $request->genero,
            'tipo_usuario' => 'P',
            'password' => Hash::make($request->password),
        ]);

        Paciente::create(['usuario_id' => $usuario->id]);
        return redirect()->route('pacientes.index')->with('success', 'Paciente creado correctamente.');
    }

    public function edit(Paciente $paciente)
    {
        $paciente->load('usuario');
        return Inertia::render('Pacientes/Edit', [
            'paciente' => $paciente,
        ]);
    }

    public function update(PacienteRequest $request, Paciente $paciente)
    {
        $usuario = $paciente->usuario;
        $usuario->ci = $request->ci;
        $usuario->nombres = $request->nombres;
        $usuario->apellido_paterno = $request->apellido_paterno;
        $usuario->apellido_materno = $request->apellido_materno;
        $usuario->telefono = $request->telefono;
        $usuario->correo = $request->correo;
        $usuario->edad = $request->edad;
        $usuario->genero = $request->genero;
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }
        $usuario->save();
        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado correctamente.');
    }

    public function destroy(Paciente $paciente)
    {
        $usuario = $paciente->usuario;
        $paciente->delete();
        if ($usuario) {
            $usuario->delete();
        }
        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado.');
    }
}
