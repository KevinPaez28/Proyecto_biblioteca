<?php

namespace App\Http\Requests\Imports;

use Illuminate\Foundation\Http\FormRequest;

class ImportApprenticeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:5120'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'El archivo es obligatorio',
            'file.file' => 'Debe ser un archivo válido',
            'file.mimes' => 'El archivo debe ser formato Excel',
            'file.max' => 'El archivo debe pesar máximo 5MB',
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => 'archivo'
        ];
    }
}
