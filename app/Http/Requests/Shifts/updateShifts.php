<?php

namespace App\Http\Requests\Shifts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateShifts extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id'); // jornada actual

        return [
            'nombre' => [
                'required',
                'string',
                'min:3',
                Rule::unique('shifts', 'name')->ignore($id),
            ],

            'horario_id' => [
                'nullable',
                'integer',
                Rule::unique('shifts', 'schedules_id')->ignore($id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la jornada es obligatorio.',
            'nombre.string'   => 'El nombre debe ser texto.',
            'nombre.min'      => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.unique'   => 'Este nombre de jornada ya existe.',

            'horario_id.integer' => 'El horario debe ser un número.',
            'horario_id.unique'  => 'Este horario ya está asignado a otra jornada.',
        ];
    }
}
