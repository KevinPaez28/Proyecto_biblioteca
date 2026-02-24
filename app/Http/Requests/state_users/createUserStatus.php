<?php

namespace App\Http\Requests\state_users;

use Illuminate\Foundation\Http\FormRequest;

class createUserStatus extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     *
     * Por defecto, está establecido en `false`.  Deberías revisar y modificar
     * esto según tus necesidades de autorización.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Define las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'nombre' es obligatorio, debe ser una cadena de texto y no debe exceder los 50 caracteres.
            'nombre' => 'required|string|max:50'
        ];
    }

    /**
     * Define los mensajes de error personalizados para las reglas de validación.
     */
    public function messages(): array
    {
        return [
            // Mensajes personalizados para las reglas de validación del campo 'nombre'.
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El campo debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
        ];
    }
}
