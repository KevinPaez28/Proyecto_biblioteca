<?php

namespace App\Http\Requests\Schedules;

use Illuminate\Foundation\Http\FormRequest;

class createSchedules extends FormRequest
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
            'hora_inicio' => 'required|string|',
            'hora_fin'    => 'required|string|after:hora_inicio',
            'nombre'  => 'required|string|min:3',

        ];
    }

    public function messages(): array
    {
        return [
            'hora_inicio.required'     => 'La hora de inicio es obligatoria.',
            'hora_inicio.string'       => 'La hora de inicio debe ser texto.',

            'hora_fin.required'        => 'La hora de fin es obligatoria.',
            'hora_fin.string'          => 'La hora de fin debe ser texto.',
            'hora_fin.date_format'     => 'La hora de fin debe tener el formato HH:MM.',
            'hora_fin.after'           => 'La hora de fin debe ser mayor que la hora de inicio.',
            'nombre.required'          => 'El nombre de la jornada es obligatorio.',
            'nombre.string'            => 'El nombre debe ser texto.',
            'nombre.min'               => 'El nombre debe tener al menos 3 caracteres.',
        ];
    }
}
