<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Http\Requests\ServicioRequest;
use Inertia\Inertia;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::orderBy('id', 'asc')->paginate(10);
        return Inertia::render('Servicios/Index', ['servicios' => $servicios]);
    }

    public function create()
    {
        return Inertia::render('Servicios/Create');
    }

    public function store(ServicioRequest $request)
    {
        Servicio::create($request->validated());
        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function edit(Servicio $servicio)
    {
        return Inertia::render('Servicios/Edit', ['servicio' => $servicio]);
    }

    public function update(ServicioRequest $request, Servicio $servicio)
    {
        $servicio->update($request->validated());
        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado.');
    }
}
