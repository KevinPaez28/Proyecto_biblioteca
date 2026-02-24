<?php

namespace App\Http\Requests\Rooms;

use Illuminate\Foundation\Http\FormRequest;

class createRooms extends FormRequest
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
            // El campo 'nombre' es obligatorio y debe ser una cadena de texto.
            'nombre'        => 'required|string',
            // El campo 'descripcion' es obligatorio y debe ser una cadena de texto.
            'descripcion'   => 'required|string',
            // El campo 'estado_id' es obligatorio y debe existir en la tabla 'state_rooms'.
            'estado_id'   => 'required|exists:state_rooms,id',
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
            // Mensajes para el campo 'nombre'.
            'nombre.required'        => 'El nombre es obligatorio.',
            'nombre.string'          => 'El nombre debe ser un texto válido.',

            // Mensajes para el campo 'descripcion'.
            'descripcion.required'   => 'La descripción es obligatoria.',
            'descripcion.string'     => 'La descripción debe ser un texto válido.',

            // Mensajes para el campo 'estado_id'.
            'estado_id.required'   => 'El estado de la sala es obligatorio.',
            'estado_id.exists'     => 'El estado seleccionado no es válido.',
        ];
    }

}
