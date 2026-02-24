<?php

namespace App\Http\Requests\Shifts;

use Illuminate\Foundation\Http\FormRequest;

class createShifts extends FormRequest
{
    
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
            // El campo 'nombre' es obligatorio, debe ser una cadena de texto y tener al menos 3 caracteres.
            'nombre'  => 'required|string|min:3',
            // El campo 'horario_id' debe ser numérico y existir en la tabla 'schedules'.
            'horario_id' => 'numeric|exists:schedules,id',
        ];
    }


    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la jornada es obligatorio.',
            'nombre.string'   => 'El nombre debe ser texto.',
            'nombre.min'      => 'El nombre debe tener al menos 3 caracteres.',

            'horario_id.numeric' => 'El horario debe ser un número.',
        ];
    }
}
