<?php

namespace App\Http\Controllers;

use App\Models\Promocion;
use App\Http\Requests\PromocionRequest;
use Inertia\Inertia;

class PromocionController extends Controller
{
    public function index()
    {
        $promociones = Promocion::orderByDesc('id')->paginate(10);
        return Inertia::render('Promociones/Index', [
            'promociones' => $promociones,
        ]);
    }

    public function create()
    {
        return Inertia::render('Promociones/Create', [
        'productos' => \App\Models\Producto::all(['id','nombre']),
        'servicios' => \App\Models\Servicio::all(['id','nombre']),
        ]);
    }

    public function store(PromocionRequest $request)
    {
        $promocion = Promocion::create($request->validated());

    // Asociar productos y servicios
    $promocion->productos()->sync($request->productos ?? []);
    $promocion->servicios()->sync($request->servicios ?? []);

    return redirect()->route('promociones.index')->with('success', 'Promoción creada correctamente.');
    }

    public function edit(Promocion $promocion)
    {
        return Inertia::render('Promociones/Edit', [
        'promocion' => $promocion,
        'productos' => \App\Models\Producto::all(['id', 'nombre']),
        'servicios' => \App\Models\Servicio::all(['id', 'nombre']),
        // Estos son los IDs de los productos y servicios asociados:
        'productos_asociados' => $promocion->productos->pluck('id')->toArray(),
        'servicios_asociados' => $promocion->servicios->pluck('id')->toArray(),
    ]);
    }

    public function update(PromocionRequest $request, Promocion $promocion)
    {
        $promocion->update($request->validated());

        $promocion->productos()->sync($request->productos ?? []);
        $promocion->servicios()->sync($request->servicios ?? []);

        return redirect()->route('promociones.index')->with('success', 'Promoción actualizada correctamente.');
    }

    public function destroy(Promocion $promocion)
    {
        $promocion->delete();
        return redirect()->route('promociones.index')->with('success', 'Promoción eliminada.');
    }
}
