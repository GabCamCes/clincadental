<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EspecialidadRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('especialidad')?->id;

        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('especialidades', 'nombre')->ignore($id),
            ],
        ];
    }
    
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.unique' => 'Ya existe una especialidad con ese nombre.',
        ];
    }
}
