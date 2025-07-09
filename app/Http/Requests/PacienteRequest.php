<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PacienteRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Validaciones solo para datos del usuario que es paciente
            'ci' => 'required|string|max:15|unique:usuarios,ci,' . $this->usuario_id,
            'nombres' => 'required|string|max:100',
            'apellido_paterno' => 'nullable|string|max:50',
            'apellido_materno' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:15',
            'correo' => 'required|email|max:100|unique:usuarios,correo,' . $this->usuario_id,
            'edad' => 'required|integer|min:0',
            'genero' => 'required|in:M,F',
            'password' => $this->isMethod('post') ? 'required|string|min:4' : 'nullable|string|min:4',
        ];
    }

    public function messages()
    {
        return [
            'ci.required' => 'El CI es obligatorio.',
            'ci.unique' => 'Este CI ya está registrado.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.unique' => 'El correo ya está registrado.',
        ];
    }
}
