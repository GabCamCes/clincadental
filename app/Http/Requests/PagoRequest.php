<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PagoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'venta_id'     => 'required|exists:ventas,id',
            'fecha_pago'   => 'required|date',
            'monto_pagado' => 'required|numeric|min:0',
            'metodo_pago'  => 'required|in:Efectivo,Transferencia',
            'estado_pago'  => 'required|in:Pendiente,Pagado,Rechazado',
        ];
    }

    public function messages()
    {
        return [
            'venta_id.required' => 'Debe asociar el pago a una venta.',
            'monto_pagado.required' => 'El monto es obligatorio.',
            'metodo_pago.required' => 'Seleccione un método de pago.',
            'estado_pago.required' => 'Seleccione el estado del pago.',
        ];
    }
}
