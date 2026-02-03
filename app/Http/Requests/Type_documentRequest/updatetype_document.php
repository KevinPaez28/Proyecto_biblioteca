<?php

namespace App\Http\Requests\TypeDocument;

use Illuminate\Foundation\Http\FormRequest;

class updateTypeDocument extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'nombre'   => "required|string|max:50|unique:document_types,name,{$id}",
            'acronimo' => "required|string|max:10|unique:document_types,acronym,{$id}",
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del tipo de documento es obligatorio.',
            'nombre.string'   => 'El nombre debe ser texto.',
            'nombre.max'      => 'Máximo 50 caracteres.',
            'nombre.unique'   => 'Este tipo de documento ya existe.',

            'acronimo.required' => 'El acronimo es obligatorio.',
            'acronimo.string'   => 'El acronimo debe ser texto.',
            'acronimo.max'      => 'Máximo 10 caracteres.',
            'acronimo.unique'   => 'Este acronimo ya está registrado.',
        ];
    }
}
