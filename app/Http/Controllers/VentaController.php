<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\ProductoVenta;
use App\Models\Producto;
use App\Models\Paciente;
use App\Models\Usuario;
use App\Http\Requests\VentaRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with('paciente.usuario', 'usuario', 'productos.producto')
            ->orderBy('fecha_venta', 'desc')
            ->paginate(10);

        return Inertia::render('Ventas/Index', [
            'ventas' => $ventas
        ]);
    }

    public function create()
    {
        return Inertia::render('Ventas/Create', [
            'pacientes' => Paciente::with('usuario')->get(),
            'productos' => Producto::all(),
            'usuarios'  => Usuario::all(),
        ]);
    }

    public function store(VentaRequest $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();

            $venta = Venta::create([
                'usuario_id'  => $data['usuario_id'],
                'paciente_id' => $data['paciente_id'],
                'fecha_venta' => $data['fecha_venta'],
                'total'       => 0,
                'metodo_pago' => $data['metodo_pago'],
            ]);

            $total = 0;
            foreach ($data['productos'] as $p) {
                $producto = Producto::find($p['producto_id']);
                $subtotal = $producto->precio * $p['cantidad'];
                $total += $subtotal;

                ProductoVenta::create([
                    'venta_id'       => $venta->id,
                    'producto_id'    => $producto->id,
                    'cantidad'       => $p['cantidad'],
                    'precio_unitario'=> $producto->precio,
                ]);
                // Descontar stock si deseas: $producto->decrement('stock', $p['cantidad']);
            }
            $venta->total = $total;
            $venta->save();
        });

        return redirect()->route('ventas.index')->with('success', 'Venta registrada correctamente.');
    }

    public function show(Venta $venta)
    {
        return Inertia::render('Ventas/Show', [
            'venta' => $venta->load('paciente.usuario', 'usuario', 'productos.producto'),
        ]);
    }

    public function edit(Venta $venta)
    {
        return Inertia::render('Ventas/Edit', [
            'venta'     => $venta->load('productos.producto'),
            'pacientes' => Paciente::with('usuario')->get(),
            'productos' => Producto::all(),
            'usuarios'  => Usuario::all(),
        ]);
    }

    public function update(VentaRequest $request, Venta $venta)
    {
        DB::transaction(function () use ($request, $venta) {
            $data = $request->validated();
            $venta->update([
                'usuario_id'  => $data['usuario_id'],
                'paciente_id' => $data['paciente_id'],
                'fecha_venta' => $data['fecha_venta'],
                'metodo_pago' => $data['metodo_pago'],
            ]);
            $venta->productos()->delete();
            $total = 0;
            foreach ($data['productos'] as $p) {
                $producto = Producto::find($p['producto_id']);
                $subtotal = $producto->precio * $p['cantidad'];
                $total += $subtotal;
                ProductoVenta::create([
                    'venta_id'       => $venta->id,
                    'producto_id'    => $producto->id,
                    'cantidad'       => $p['cantidad'],
                    'precio_unitario'=> $producto->precio,
                ]);
            }
            $venta->total = $total;
            $venta->save();
        });

        return redirect()->route('ventas.index')->with('success', 'Venta actualizada correctamente.');
    }

    public function destroy(Venta $venta)
    {
        $venta->productos()->delete();
        $venta->delete();
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada correctamente.');
    }
}
