<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Http\Requests\SalaRequest;
use Inertia\Inertia;

class SalaController extends Controller
{
    public function index()
    {
        $salas = Sala::orderBy('id', 'asc')->paginate(10);
        return Inertia::render('Salas/Index', ['salas' => $salas]);
    }

    public function create()
    {
        return Inertia::render('Salas/Create');
    }

    public function store(SalaRequest $request)
    {
        Sala::create($request->validated());
        return redirect()->route('salas.index')->with('success', 'Sala creada correctamente.');
    }

    public function edit(Sala $sala)
    {
        return Inertia::render('Salas/Edit', ['sala' => $sala]);
    }

    public function update(SalaRequest $request, Sala $sala)
    {
        $sala->update($request->validated());
        return redirect()->route('salas.index')->with('success', 'Sala actualizada.');
    }

    public function destroy(Sala $sala)
    {
        $sala->delete();
        return redirect()->route('salas.index')->with('success', 'Sala eliminada.');
    }
}
