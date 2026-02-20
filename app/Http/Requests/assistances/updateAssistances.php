<?php

namespace App\Http\Requests\assistances;

use Illuminate\Foundation\Http\FormRequest;

class updateAssistances extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición.
     *
     * @return bool
     */
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     *
     */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // ID de la asistencia que se está actualizando
        $id = $this->route('id'); 

        return [
            // ID del usuario, es obligatorio, debe ser un entero y debe existir en la tabla 'users'.
            'user_id'         => 'required|integer|exists:users,id', 
            // ID de la jornada laboral, es obligatorio, debe ser un entero y debe existir en la tabla 'shifts'.
            'working_day_id'  => 'required|integer|exists:shifts,id', 
            // ID del motivo, es obligatorio, debe ser un entero y debe existir en la tabla 'reasons'.
            'reason_id'       => 'required|integer|exists:reasons,id', 
            // ID del evento, es obligatorio, debe ser un entero y debe existir en la tabla 'events'.
            'event_id'        => 'required|integer|exists:events,id', 
        ];
    }

    /**
     * Obtiene los mensajes de error para las reglas de validación definidas.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Mensajes para el campo 'user_id'.
            'user_id.required'        => 'El usuario es obligatorio.', // Si no se proporciona el usuario.
            'user_id.integer'         => 'El usuario debe ser un número.', // Si el usuario no es un número entero.
            'user_id.exists'          => 'El usuario seleccionado no existe.', // Si el usuario no existe en la tabla 'users'.

            // Mensajes para el campo 'working_day_id'.
            'working_day_id.required' => 'La jornada laboral es obligatoria.', // Si no se proporciona la jornada laboral.
            'working_day_id.integer'  => 'La jornada laboral debe ser un número.', // Si la jornada laboral no es un número entero.
            'working_day_id.exists'   => 'La jornada seleccionada no existe.', // Si la jornada laboral no existe en la tabla 'shifts'.

            // Mensajes para el campo 'reason_id'.
            'reason_id.required'      => 'El motivo es obligatorio.', // Si no se proporciona el motivo.
            'reason_id.integer'       => 'El motivo debe ser un número.', // Si el motivo no es un número entero.
            'reason_id.exists'        => 'El motivo seleccionado no existe.', // Si el motivo no existe en la tabla 'reasons'.

            // Mensajes para el campo 'event_id'.
            'event_id.required'       => 'El evento es obligatorio.', // Si no se proporciona el evento.
            'event_id.integer'        => 'El evento debe ser un número.', // Si el evento no es un número entero.
            'event_id.exists'         => 'El evento seleccionado no existe.', // Si el evento no existe en la tabla 'events'.
        ];
    }
}
