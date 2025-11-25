<?php

namespace App\Http\Requests\Shifts;

use Illuminate\Foundation\Http\FormRequest;

class createShifts extends FormRequest
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
        return [
            'nombre'  => 'required|string|min:3',
            'horario_id' => 'required|numeric|exists:schedules,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la jornada es obligatorio.',
            'nombre.string'   => 'El nombre debe ser texto.',
            'nombre.min'      => 'El nombre debe tener al menos 3 caracteres.',

            'horario_id.required' => 'El horario es obligatorio.',
            'horario_id.numeric' => 'El horario debe ser numero.',
        ];
    }
}
