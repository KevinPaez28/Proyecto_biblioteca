<?php

namespace App\Http\Requests\Shifts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateShifts extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
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
        // Obtiene el ID de la jornada actual de la ruta.
        $id = $this->route('id');

        return [
            // Reglas para el campo 'nombre'.
            'nombre' => [
                'required', // Es obligatorio.
                'string',   // Debe ser una cadena de texto.
                'min:3',    // Debe tener al menos 3 caracteres.
                // Debe ser único en la tabla 'shifts' en la columna 'name', ignorando el registro con el ID actual.
                Rule::unique('shifts', 'name')->ignore($id),
            ],

            // Reglas para el campo 'horario_id'.
            'horario_id' => [
                //No es obligatorio
                'nullable',
                //Debe ser un entero
                'integer',
                Rule::unique('shifts', 'schedules_id')->ignore($id),
            ],
        ];
    }

    public function messages(): array
    /**
     * Define los mensajes de error personalizados para las reglas de validación.
     */
    {
        return [
            'nombre.required' => 'El nombre de la jornada es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.min'      => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.unique'   => 'Este nombre de jornada ya existe.',

            'horario_id.integer' => 'El horario debe ser un número.',
            'horario_id.unique'  => 'Este horario ya está asignado a otra jornada.',
        ];
    }
}
