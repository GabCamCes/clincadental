<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Venta;
use App\Http\Requests\PagoRequest;
use Inertia\Inertia;
use App\Models\HistorialPago;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PagoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Asume relación paciente en el modelo User: $user->paciente->id
        $pacienteId = $user->paciente->id ?? null;
        $pagos = Pago::with(['venta.paciente.usuario'])
            ->whereHas('venta', function($query) use ($pacienteId) {
                $query->where('paciente_id', $pacienteId);
            })
            ->orderBy('fecha_pago', 'desc')
            ->paginate(10);

        return Inertia::render('Pagos/Index', [
            'pagos' => $pagos,
        ]);
    }

    public function create()
    {
        return Inertia::render('Pagos/Create', [
            'ventas' => Venta::with('paciente.usuario')->get(),
        ]);
    }

    public function store(PagoRequest $request)
    {
        $user = Auth::user();
        $pacienteId = $user->paciente->id ?? null;
        // Forzar que el pago sea para el paciente autenticado
        $data = $request->validated();
        // Si el modelo Venta está relacionado, asegurarse que la venta pertenezca al paciente
        $venta = \App\Models\Venta::find($data['venta_id']);
        if (!$venta || $venta->paciente_id !== $pacienteId) {
            abort(403);
        }
        $pago = Pago::create($data);

        // Historial
        HistorialPago::create([
            'pago_id'      => $pago->id,
            'usuario_id'   => Auth::id(),
            'accion'       => 'registrado',
            'monto'        => $pago->monto_pagado,
            'tipo_pago'    => $pago->metodo_pago,
            'observaciones'=> 'Pago creado',
            'fecha_evento' => now(),
        ]);

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    public function edit(Pago $pago)
    {
        $user = Auth::user();
        $pacienteId = $user->paciente->id ?? null;
        if ($pago->venta->paciente_id !== $pacienteId) {
            abort(403);
        }
        return Inertia::render('Pagos/Edit', [
            'pago' => $pago,
            'ventas' => Venta::with('paciente.usuario')->get(),
        ]);
    }

    public function update(PagoRequest $request, Pago $pago)
    {
        $user = Auth::user();
        $pacienteId = $user->paciente->id ?? null;
        if ($pago->venta->paciente_id !== $pacienteId) {
            abort(403);
        }
        $pago->update($request->validated());

        // Historial
        HistorialPago::create([
            'pago_id'      => $pago->id,
            'usuario_id'   => Auth::id(),
            'accion'       => 'modificado',
            'monto'        => $pago->monto_pagado,
            'tipo_pago'    => $pago->metodo_pago,
            'observaciones'=> 'Pago modificado',
            'fecha_evento' => now(),
        ]);

        return redirect()->route('pagos.index')->with('success', 'Pago actualizado correctamente.');
    }

    public function show(Pago $pago)
    {
        $user = Auth::user();
        $pacienteId = $user->paciente->id ?? null;
        if ($pago->venta->paciente_id !== $pacienteId) {
            abort(403);
        }
        // Datos para el QR (versión simplificada para presentación)
        $qrData = [
            'beneficiario' => 'Arte Dental',
            'banco' => 'Banco Ganadero',
            'monto' => number_format($pago->monto_pagado, 2, ',', '.'),
            'referencia' => 'PAGO-' . str_pad($pago->id, 6, '0', STR_PAD_LEFT),
            'fecha_vencimiento' => now()->addDays(3)->format('d/m/Y'),
            'concepto' => 'Servicios dentales - Venta #' . $pago->venta_id
        ];

        // Formato legible para el QR
        $qrText = "BANCO: {$qrData['banco']}\n";
        $qrText .= "BENEFICIARIO: {$qrData['beneficiario']}\n";
        $qrText .= "MONTO: Bs. {$qrData['monto']}\n";
        $qrText .= "REFERENCIA: {$qrData['referencia']}\n";
        $qrText .= "VENCE: {$qrData['fecha_vencimiento']}\n";
        $qrText .= "CONCEPTO: {$qrData['concepto']}";

        // Generar QR
        $qr = \QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->errorCorrection('H')
            ->generate($qrText);

        $qrBase64 = 'data:image/png;base64,' . base64_encode($qr);

        return Inertia::render('Pagos/Show', [
            'pago' => $pago->load('venta.paciente.usuario'),
            'qr' => $qrBase64,
            'qrData' => $qrData
        ]);
    }

    public function destroy(Pago $pago)
    {
        $user = Auth::user();
        $pacienteId = $user->paciente->id ?? null;
        if ($pago->venta->paciente_id !== $pacienteId) {
            abort(403);
        }
        $pago->delete();

        // Historial
        HistorialPago::create([
            'pago_id'      => $pago->id,
            'usuario_id'   => Auth::id(),
            'accion'       => 'anulado',
            'monto'        => $pago->monto_pagado,
            'tipo_pago'    => $pago->metodo_pago,
            'observaciones'=> 'Pago anulado',
            'fecha_evento' => now(),
        ]);

        return redirect()->route('pagos.index')->with('success', 'Pago eliminado correctamente.');
    }
}

