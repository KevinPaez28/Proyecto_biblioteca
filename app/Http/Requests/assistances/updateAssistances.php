<?php

namespace App\Http\Requests\assistances;

use Illuminate\Foundation\Http\FormRequest;

class updateAssistances extends FormRequest
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
        $id = $this->route('id'); // ID de la asistencia que se está actualizando

        return [
            'user_id'         => 'required|integer|exists:users,id',
            'working_day_id'  => 'required|integer|exists:shifts,id',
            'reason_id'       => 'required|integer|exists:reasons,id',
            'event_id'        => 'required|integer|exists:events,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'        => 'El usuario es obligatorio.',
            'user_id.integer'         => 'El usuario debe ser un número.',
            'user_id.exists'          => 'El usuario seleccionado no existe.',

            'working_day_id.required' => 'La jornada laboral es obligatoria.',
            'working_day_id.integer'  => 'La jornada laboral debe ser un número.',
            'working_day_id.exists'   => 'La jornada seleccionada no existe.',

            'reason_id.required'      => 'El motivo es obligatorio.',
            'reason_id.integer'       => 'El motivo debe ser un número.',
            'reason_id.exists'        => 'El motivo seleccionado no existe.',

            'event_id.required'       => 'El evento es obligatorio.',
            'event_id.integer'        => 'El evento debe ser un número.',
            'event_id.exists'         => 'El evento seleccionado no existe.',
        ];
    }
}
