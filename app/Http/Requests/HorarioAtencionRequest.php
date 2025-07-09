<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HorarioAtencionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'medico_id'   => 'required|exists:medicos,id',
            'dia_semana'  => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin'    => 'required|date_format:H:i|after:hora_inicio',
        ];
    }

    public function messages()
    {
        return [
            'dia_semana.required' => 'El día de la semana es obligatorio.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_fin.required' => 'La hora de fin es obligatoria.',
            'hora_fin.after' => 'La hora de fin debe ser después de la de inicio.',
        ];
    }
}

