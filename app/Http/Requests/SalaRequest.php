<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SalaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('sala')?->id;

        return [
            'nombre' => [
                'required', 'string', 'max:50',
                Rule::unique('salas', 'nombre')->ignore($id)
            ],
            'descripcion' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe una sala con ese nombre.',
        ];
    }
}
