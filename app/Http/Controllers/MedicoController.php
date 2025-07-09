<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Usuario;
use App\Models\Especialidad;
use App\Http\Requests\MedicoRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class MedicoController extends Controller
{
    public function index()
    {
        $medicos = Medico::with(['usuario', 'especialidad'])->orderByDesc('id')->paginate(10);
        return Inertia::render('Medicos/Index', [
            'medicos' => $medicos,
        ]);
    }

    public function create()
    {
        $especialidades = Especialidad::orderBy('nombre')->get();
        return Inertia::render('Medicos/Create', [
            'especialidades' => $especialidades,
        ]);
    }

    public function store(MedicoRequest $request)
    {
        $usuario = Usuario::create([
            'ci' => $request->ci,
            'nombres' => $request->nombres,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'edad' => $request->edad,
            'genero' => $request->genero,
            'tipo_usuario' => 'M',
            'password' => Hash::make($request->password),
        ]);

        Medico::create([
            'usuario_id' => $usuario->id,
            'especialidad_id' => $request->especialidad_id,
        ]);
        return redirect()->route('medicos.index')->with('success', 'Médico creado correctamente.');
    }

    public function edit(Medico $medico)
    {
        $medico->load(['usuario', 'especialidad']);
        $especialidades = Especialidad::orderBy('nombre')->get();
        return Inertia::render('Medicos/Edit', [
            'medico' => $medico,
            'especialidades' => $especialidades,
        ]);
    }

    public function update(MedicoRequest $request, Medico $medico)
    {
        $usuario = $medico->usuario;
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

        $medico->especialidad_id = $request->especialidad_id;
        $medico->save();

        return redirect()->route('medicos.index')->with('success', 'Médico actualizado correctamente.');
    }

    public function destroy(Medico $medico)
    {
        $usuario = $medico->usuario;
        $medico->delete();
        if ($usuario) {
            $usuario->delete();
        }
        return redirect()->route('medicos.index')->with('success', 'Médico eliminado.');
    }
}
