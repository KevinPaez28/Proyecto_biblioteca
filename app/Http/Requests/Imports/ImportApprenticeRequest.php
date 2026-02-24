<?php

namespace App\Http\Requests\Imports;

use Illuminate\Foundation\Http\FormRequest;

class ImportApprenticeRequest extends FormRequest
{
    /**
     * Autoriza al usuario a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Define las reglas de validación para la solicitud.
     */
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

    /**
     *  Define los mensajes de error personalizados para las reglas de validación.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'El archivo es obligatorio',
            'file.file' => 'Debe ser un archivo válido',
            'file.mimes' => 'El archivo debe ser formato Excel',
            'file.max' => 'El archivo debe pesar máximo 5MB',
        ];
    }

    /**
     *  Define los atributos personalizados para las reglas de validación.
     */
    public function attributes(): array
    {
        return [
            'file' => 'archivo'
        ];
    }
}
