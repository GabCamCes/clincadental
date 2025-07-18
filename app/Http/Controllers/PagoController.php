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

        if ($user->tipo_usuario === 'A') {
            // El administrador ve todos los pagos
            $pagos = Pago::with(['venta.paciente.usuario'])
                ->orderBy('fecha_pago', 'desc')
                ->paginate(10);
        } else {
            // Paciente: solo sus pagos
            $pacienteId = $user->paciente->id ?? null;
            $pagos = Pago::with(['venta.paciente.usuario'])
                ->whereHas('venta', function ($query) use ($pacienteId) {
                    $query->where('paciente_id', $pacienteId);
                })
                ->orderBy('fecha_pago', 'desc')
                ->paginate(10);
        }

        return Inertia::render('Pagos/Index', [
            'pagos' => $pagos,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        abort_if($user->tipo_usuario !== 'A', 403);

        return Inertia::render('Pagos/Create', [
            'ventas' => Venta::with('paciente.usuario')->get(),
        ]);
    }

    public function store(PagoRequest $request)
    {
        $user = Auth::user();
        abort_if($user->tipo_usuario !== 'A', 403);

        $data = $request->validated();
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
        abort_if($user->tipo_usuario !== 'A', 403);
        return Inertia::render('Pagos/Edit', [
            'pago' => $pago,
            'ventas' => Venta::with('paciente.usuario')->get(),
        ]);
    }

    public function update(PagoRequest $request, Pago $pago)
    {
        $user = Auth::user();
        abort_if($user->tipo_usuario !== 'A', 403);

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
        if ($user->tipo_usuario === 'P') {
            $pacienteId = $user->paciente->id ?? null;
            abort_if($pago->venta->paciente_id !== $pacienteId, 403);
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
        abort_if($user->tipo_usuario !== 'A', 403);

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

