<?php

namespace App\Http\Controllers;

use App\Models\HistorialPago;
use Inertia\Inertia;

class HistorialPagoController extends Controller
{
    public function index()
    {
        $historial = HistorialPago::with('pago.venta.paciente.usuario', 'usuario')
            ->orderBy('fecha_evento', 'desc')
            ->paginate(15);

        return Inertia::render('HistorialPagos/Index', [
            'historial' => $historial
        ]);
    }
}
