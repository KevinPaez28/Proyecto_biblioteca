<?php

namespace App\Http\Requests\Reset;

use Illuminate\Foundation\Http\FormRequest;

class requestEmail extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document' => 'required|exists:users,document',
        ];
    }

    /**
     * Define los mensajes de error personalizados para las reglas de validación.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'document.required' => 'El documento es obligatorio.',
            'document.exists' => 'El documento no existe en el sistema.',
        ];
    }

}
