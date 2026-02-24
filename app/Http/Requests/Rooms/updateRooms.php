<?php

namespace App\Http\Requests\Rooms;

use Illuminate\Foundation\Http\FormRequest;

class updateRooms extends FormRequest
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
        // Obtiene el ID de la sala que se está actualizando.
        $Id = $this->route('id');

        return [
            // El campo 'nombre' es obligatorio, debe ser una cadena de texto,
            // debe tener al menos 3 caracteres y debe ser único en la tabla 'rooms',
            // excluyendo la sala que se está actualizando (identificada por $Id).
            'nombre' => "required|string|min:3|unique:rooms,name,{$Id}",
            // El campo 'descripcion' es obligatorio y debe ser una cadena de texto.
            'descripcion'   => 'required|string',
            // El campo 'estado_sala' es obligatorio y debe existir en la tabla 'state_rooms'.
            'estado_sala'   => 'required|exists:state_rooms,id',
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

            // Mensajes para el campo 'estado_sala'.
            'estado_sala.required'   => 'El estado de la sala es obligatorio.',
            'estado_sala.exists'     => 'El estado seleccionado no es válido.',
        ];
    }

}