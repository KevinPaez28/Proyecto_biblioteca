<?php

namespace App\Http\Requests\Schedules;

use Illuminate\Foundation\Http\FormRequest;

class updateSchedules extends FormRequest
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
        $Id = $this->route('id');

        return [
            'hora_inicio' => "required|string|unique:schedules,id,{$Id}",
            'hora_fin'    => 'required|string|after:hora_inicio',
        ];
    }

    /**
     * Define los mensajes de error personalizados para las reglas de validación.
     */
    public function messages(): array
    {
        return [
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_inicio.string'   => 'La hora de inicio debe ser texto.',
            'hora_inicio.unique'   => 'Esta hora de inicio ya está registrada.',

            'hora_fin.required'    => 'La hora de fin es obligatoria.',
            'hora_fin.string'      => 'La hora de fin debe ser texto.',
            'hora_fin.after'       => 'La hora de fin debe ser mayor que la hora de inicio.',
        ];
    }
}
