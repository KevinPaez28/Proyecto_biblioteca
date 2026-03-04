<?php

namespace App\Http\Requests\TypeDocument;

use Illuminate\Foundation\Http\FormRequest;

class Createtypedocument extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:50|unique:document_types,name',
            'acronimo'  => 'required|string|max:10|unique:document_types,acronym',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del tipo de documento es obligatorio.',
            'nombre.string'   => 'El nombre debe ser texto.',
            'nombre.max'      => 'Máximo 50 caracteres.',
            'nombre.unique'   => 'Este tipo de documento ya existe.',

            'acronimo.required' => 'El acronimo es obligatoria.',
            'acronimo.string'   => 'El acronimo debe ser texto.',
            'acronimo.max'      => 'Máximo 10 caracteres.',
            'acronimo.unique'   => 'Este acronimo ya está registrada.',
        ];
    }
}
