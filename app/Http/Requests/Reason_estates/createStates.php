<?php

namespace App\Http\Requests\Reason_estates;

use Illuminate\Foundation\Http\FormRequest;

class createStates extends FormRequest
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
            'nombre' => 'required|string|max:50'
        ];
    }

   
    public function messages(): array
    /**
     *  Define los mensajes de error personalizados para las reglas de validación.
     */
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El campo debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
        ];
    }
}
