<?php

namespace App\Http\Requests\state_rooms;

use Illuminate\Foundation\Http\FormRequest;

class updatestate_rooms extends FormRequest
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
        // Obtiene el ID del estado de la sala que se va a actualizar.
        $Id = $this->route('id');

        return [
            // El campo 'nombre' es obligatorio, debe ser una cadena de texto,
            // debe tener al menos 3 caracteres y debe ser único en la tabla 'state_rooms',
            // excluyendo el estado de la sala que se está actualizando (identificado por $Id).
            'nombre' => "required|string|min:3|unique:state_rooms,id,{$Id}",
        ];
    }

   /**
    * Define los mensajes de error personalizados para las reglas de validación.
    */
    public function messages(): array
    {
        return [
            'nombre.required'        => 'El nombre es obligatorio.',
            'nombre.string'          => 'El nombre debe ser un texto válido.'
        ];
    }
}
