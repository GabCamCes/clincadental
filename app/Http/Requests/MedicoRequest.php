<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $usuarioId = $this->route('medico') ? $this->route('medico')->usuario_id : null;

        return [
            'ci' => 'required|string|max:15|unique:usuarios,ci,' . $usuarioId,
            'nombres' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:50',
            'apellido_materno' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:15',
            'correo' => 'required|email|max:100|unique:usuarios,correo,' . $usuarioId,
            'edad' => 'required|integer|min:0',
            'genero' => 'required|in:M,F',
            'especialidad_id' => 'required|exists:especialidades,id',
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
            'especialidad_id.required' => 'Debe elegir una especialidad.',
            'especialidad_id.exists' => 'La especialidad seleccionada no existe.',
        ];
    }
}
