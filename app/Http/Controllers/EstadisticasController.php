<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\Paciente;
use App\Models\Medico;
use App\Models\Cita;
use App\Models\Producto;
use App\Models\Servicio;

class EstadisticasController extends Controller
{
    public function index()
    {
        // Productos más vendidos (Top 5)
        $productosPopulares = DB::table('producto_venta')
            ->join('productos', 'producto_venta.producto_id', '=', 'productos.id')
            ->select('productos.nombre', DB::raw('SUM(producto_venta.cantidad) as total_vendidos'))
            ->groupBy('productos.nombre')
            ->orderByDesc('total_vendidos')
            ->limit(5)
            ->get();

        // Servicios más solicitados (Top 5)
        $serviciosPopulares = DB::table('citas')
            ->join('servicios', 'citas.servicio_id', '=', 'servicios.id')
            ->select('servicios.nombre', DB::raw('COUNT(*) as total_pedidos'))
            ->groupBy('servicios.nombre')
            ->orderByDesc('total_pedidos')
            ->limit(5)
            ->get();

        return Inertia::render('Usuarios/Estadisticas', [
            'productosPopulares' => $productosPopulares,
            'serviciosPopulares' => $serviciosPopulares
        ]);
    }

    public function dashboard()
    {
        $estadisticas = [
            'pacientes' => Paciente::count(),
            'medicos' => Medico::count(),
            'citas' => Cita::count(),
            'productos' => Producto::count(),
            'servicios' => Servicio::count()
        ];

        return Inertia::render('Usuarios/Dashboard', [
            'estadisticas' => $estadisticas
        ]);
    }
}
