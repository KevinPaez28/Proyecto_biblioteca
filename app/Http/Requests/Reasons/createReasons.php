<?php

namespace App\Http\Requests\Reasons;

use Illuminate\Foundation\Http\FormRequest;

class createReasons extends FormRequest
{
    public function authorize(): bool
    {
        // Autoriza al usuario a realizar esta solicitud.
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
            // El campo 'nombre' es obligatorio y debe ser una cadena de texto.
            'nombre' => 'required|string',

            // El campo 'descripcion' es opcional y debe ser una cadena de texto.
            // El campo 'estados_id' es obligatorio y debe existir en la tabla 'states_Reason'.

            'descripcion' => 'nullable|string|',
            'estados_id' => 'required|exists:states_Reason,id',
        ];
    }
    public function messages(): array
    {
        // Define los mensajes de error personalizados para las reglas de validación.
        return [
            // Mensajes para el campo 'nombre'.
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.string' => 'El campo Nombre debe ser un texto válido.',
            'nombre.max' => 'El campo Nombre no debe exceder los 255 caracteres.',
            'nombre.unique' => 'El Nombre ya está registrado.',

            // Mensajes para el campo 'descripcion'.
            'descripcion.string' => 'La Descripción debe ser un texto válido.',
            'descripcion.max' => 'La Descripción no debe exceder los 500 caracteres.',

            // Mensajes para el campo 'estados_id'.
            'estados_id.required' => 'El campo Estado es obligatorio.',

            // Asegura que el 'estados_id' proporcionado realmente exista en la tabla 'states_Reason'.

            'estados_id.exists' => 'El Estado seleccionado no es válido.',
        ];
    }
}
