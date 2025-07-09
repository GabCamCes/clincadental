<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromocionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'tipo_promocion' => 'required|in:Producto,Servicio',
            'porcentaje_descuento' => 'required|numeric|min:0|max:100',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|in:Activa,Inactiva',
            'productos' => 'nullable|array',
            'productos.*' => 'exists:productos,id',
            'servicios' => 'nullable|array',
            'servicios.*' => 'exists:servicios,id',
        ];
    }

    public function messages()
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'tipo_promocion.required' => 'Debe seleccionar tipo de promoción.',
            'porcentaje_descuento.required' => 'Debe indicar el porcentaje de descuento.',
            'fecha_inicio.required' => 'Fecha de inicio obligatoria.',
            'fecha_fin.required' => 'Fecha de fin obligatoria.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
            'estado.required' => 'Debe seleccionar un estado.'
        ];
    }
}
