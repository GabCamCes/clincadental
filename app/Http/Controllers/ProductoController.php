<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\ProductoRequest;
use Inertia\Inertia;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::orderBy('id', 'asc')->paginate(10);
        return Inertia::render('Productos/Index', ['productos' => $productos]);
    }

    public function create()
    {
        return Inertia::render('Productos/Create');
    }

    public function store(ProductoRequest $request)
    {
        Producto::create($request->validated());
        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        return Inertia::render('Productos/Edit', ['producto' => $producto]);
    }

    public function update(ProductoRequest $request, Producto $producto)
    {
        $producto->update($request->validated());
        return redirect()->route('productos.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado.');
    }
}
