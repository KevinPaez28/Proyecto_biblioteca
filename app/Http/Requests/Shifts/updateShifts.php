<?php

namespace App\Http\Requests\Shifts;

use Illuminate\Foundation\Http\FormRequest;

class updateShifts extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $Id = $this->route('id'); // ID de la jornada que se actualiza

        return [
            'nombre' => "required|string|min:3|unique:work_sessions,nombre,{$Id}",
            'horario_id' => 'required|integer|exists:schedules,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la jornada es obligatorio.',
            'nombre.string'   => 'El nombre debe ser texto.',
            'nombre.min'      => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.unique'   => 'Este nombre de jornada ya existe.',

            'horario_id.required' => 'El horario es obligatorio.',
            'horario_id.integer'  => 'El horario debe ser un número.',
            'horario_id.exists'   => 'El horario seleccionado no es válido.',
        ];
    }
}
