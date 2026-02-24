<?php

namespace App\Http\Requests\Reasons;

use Illuminate\Foundation\Http\FormRequest;

class updateReasons extends FormRequest
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
        // Obtiene el ID de la razón que se está actualizando desde la ruta.
        $Id = $this->route('id');

        return [
            // El campo 'nombre' es obligatorio, debe ser una cadena de texto y debe ser único en la tabla 'reasons',
            // ignorando la razón actual que se está actualizando (identificada por $Id).
            'nombre' => "required|string|unique:reasons,name,{$Id}",

            // El campo 'descripcion' es opcional y debe ser una cadena de texto.
            'descripcion' => 'nullable|string|',

            // El campo 'estados_id' es obligatorio y debe existir en la tabla 'states_Reason'.
            'estados_id' => 'required|exists:states_Reason,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.string' => 'El campo Nombre debe ser un texto válido.',
            'nombre.max' => 'El campo Nombre no debe exceder los 255 caracteres.',
            'nombre.unique' => 'El Nombre ya está registrado.',

            'descripcion.string' => 'La Descripción debe ser un texto válido.',
            'descripcion.max' => 'La Descripción no debe exceder los 500 caracteres.',

            'estados_id.required' => 'El campo Estado es obligatorio.',
            'estados_id.exists' => 'El Estado seleccionado no es válido.',
        ];
    }

}
