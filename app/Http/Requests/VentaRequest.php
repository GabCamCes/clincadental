<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'usuario_id'   => 'required|exists:usuarios,id',
            'paciente_id'  => 'required|exists:pacientes,id',
            'fecha_venta'  => 'required|date',
            'metodo_pago'  => 'required|string|max:50',
            'productos'    => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad'    => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'productos.required' => 'Debe agregar al menos un producto a la venta.',
        ];
    }
}
