<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HistorialClinicoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'cita_id'      => 'required|exists:citas,id',
            'diagnostico'  => 'required|string|max:1000',
            'tratamiento'  => 'required|string|max:1000',
            'observaciones'=> 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'cita_id.required' => 'Debe seleccionar una cita.',
            'diagnostico.required' => 'El diagnóstico es obligatorio.',
            'tratamiento.required' => 'El tratamiento es obligatorio.',
        ];
    }
}
