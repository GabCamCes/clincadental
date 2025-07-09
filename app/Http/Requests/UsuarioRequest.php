<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Cambia a true si solo los administradores pueden (o pon lógica de roles aquí)
        return true;
    }

    public function rules(): array
    {
        // Para distinguir entre store y update:
        $usuarioId = $this->route('usuario') ? $this->route('usuario')->id : null;

        return [
            'ci' => 'required|unique:usuarios,ci' . ($usuarioId ? ',' . $usuarioId : ''),
            'nombres' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'telefono' => 'nullable',
            'correo' => 'required|email|unique:usuarios,correo' . ($usuarioId ? ',' . $usuarioId : ''),
            'edad' => 'required|integer|min:0',
            'genero' => 'required|in:M,F',
            'tipo_usuario' => 'required|in:A,M,P',
            // Password solo requerido en creación, opcional en update
            'password' => $this->isMethod('post') ? 'required|min:4' : 'nullable|min:4',
        ];
    }

    public function messages()
    {
        return [
            'ci.required' => 'El CI es obligatorio.',
            'ci.unique' => 'El CI ya está registrado.',
            'nombres.required' => 'Los nombres son obligatorios.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'apellido_materno.required' => 'El apellido materno es obligatorio.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.unique' => 'El correo ya está registrado.',
            'correo.email' => 'Debe ser un correo válido.',
            'edad.required' => 'La edad es obligatoria.',
            'edad.integer' => 'La edad debe ser un número.',
            'edad.min' => 'La edad debe ser mayor o igual a 0.',
            'genero.required' => 'El género es obligatorio.',
            'genero.in' => 'El género debe ser M o F.',
            'tipo_usuario.required' => 'El tipo de usuario es obligatorio.',
            'tipo_usuario.in' => 'El tipo de usuario debe ser A, M o P.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ];
    }
}
