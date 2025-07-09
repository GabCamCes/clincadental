<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServicioRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('servicio')?->id;

        return [
            'nombre' => [
                'required', 'string', 'max:100',
                Rule::unique('servicios', 'nombre')->ignore($id)
            ],
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe un servicio con ese nombre.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número.',
        ];
    }
}
