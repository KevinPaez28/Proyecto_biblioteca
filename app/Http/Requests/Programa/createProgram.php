<?php

namespace App\Http\Requests\Programa;

use Illuminate\Foundation\Http\FormRequest;

class createProgram extends FormRequest
{
    /**
     * Autoriza al usuario a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    /**
     * Define las reglas de validación para la solicitud.
     */
    {
        return [
            'programa_formacion' => 'unique:programs,training_program|required|string|max:100'
        ];
    }


    public function messages(): array
    /**
     *  Define los mensajes de error personalizados para las reglas de validación.
     */
    {
        return [
            'programa_formacion.required' => 'El nombre del programa es obligatorio.',
            'programa_formacion.string' => 'El campo debe ser texto.',
            'programa_formacion.max' => 'El nombre del programa no puede superar los 50 caracteres.',
            'programa_formacion.unique' => 'El nombre del programa ya existe.',
        ];
    }
}
