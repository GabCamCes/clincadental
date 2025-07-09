<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use App\Http\Requests\EspecialidadRequest;
use Inertia\Inertia;

class EspecialidadController extends Controller
{
    public function index()
    {
        $especialidades = Especialidad::orderBy('id','asc')->paginate(10);
        return Inertia::render('Especialidades/Index', ['especialidades' => $especialidades]);
    }

    public function create()
    {
        return Inertia::render('Especialidades/Create');
    }

    public function store(EspecialidadRequest $request)
    {
        Especialidad::create($request->validated());
        return redirect()->route('especialidades.index')->with('success', 'Especialidad creada correctamente.');
    }

    public function edit(Especialidad $especialidad)
    {
        return Inertia::render('Especialidades/Edit', ['especialidad' => $especialidad]);
    }

    public function update(EspecialidadRequest $request, Especialidad $especialidad)
    {
        $especialidad->update($request->validated());
        return redirect()->route('especialidades.index')->with('success', 'Especialidad actualizada.');
    }

    public function destroy(Especialidad $especialidad)
    {
        $especialidad->delete();
        return redirect()->route('especialidades.index')->with('success', 'Especialidad eliminada.');
    }
}
