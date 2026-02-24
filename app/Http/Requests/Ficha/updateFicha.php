<?php

namespace App\Http\Requests\Ficha;

use Illuminate\Foundation\Http\FormRequest;

class updateFicha extends FormRequest
{
    /**
     * Define las reglas de validación para la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     *  - 'ficha': Obligatorio, máximo 20 caracteres, y único en la tabla 'ficha' ignorando el ID actual.
     *  - 'programa': Obligatorio y debe existir en la tabla 'programs'.
     */
    public function rules(): array
    {
        /**
         * Obtiene el ID de la ficha desde la ruta.
         *
         *  Se utiliza para la regla de validación 'unique' y evitar que la
         *  ficha actual se tome en cuenta al verificar la unicidad.
         */
        $fichaId = $this->route('id');

        return [
            'ficha' => "required|max:20|unique:ficha,ficha,{$fichaId}",
            'programa' => 'required|exists:programs,id',
        ];
    }

    /**
     *  Define los mensajes de error personalizados para las reglas de validación.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'ficha.required' => 'La ficha es obligatoria.',
            'ficha.string' => 'La ficha debe ser texto.',
            'ficha.max' => 'La ficha no puede superar los 20 caracteres.',
            'ficha.unique' => 'Esta ficha ya está registrada.',

            'programa.required' => 'El programa es obligatorio.',
            'programa.exists' => 'El programa seleccionado no existe.',
        ];
    }

}
