<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;

class createEvent extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'        => 'required|string|max:255',
            'encargado'     => 'required|string|max:255',
            'sala_id'       => 'required|exists:rooms,id',
            'fecha'         => 'required|date',
            'hora_inicio'   => 'required|date_format:H:i',
            'hora_fin'      => 'required|date_format:H:i|after:hora_inicio',
            'estado_id'     => 'required|exists:state_events,id', 
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'         => 'El nombre del evento es obligatorio.',
            'encargado.required'      => 'El encargado del evento es obligatorio.',

            'sala_id.required'        => 'El área es obligatoria.',
            'sala_id.exists'          => 'El área seleccionada no existe.',

            'fecha.required'          => 'La fecha es obligatoria.',
            'fecha.date'              => 'La fecha debe ser válida.',

            'hora_inicio.required'    => 'La hora de inicio es obligatoria.',
            'hora_inicio.date_format' => 'La hora de inicio debe tener formato HH:MM.',

            'hora_fin.required'       => 'La hora de fin es obligatoria.',
            'hora_fin.date_format'    => 'La hora de fin debe tener formato HH:MM.',
            'hora_fin.after'          => 'La hora de fin debe ser mayor a la hora de inicio.',

            'estado_id.required'      => 'El estado del evento es obligatorio.',
            'estado_id.exists'        => 'El estado seleccionado no existe.',
        ];
    }
}
