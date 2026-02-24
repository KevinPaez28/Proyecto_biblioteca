<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;

class updateEvent extends FormRequest
{
    /**
     * Autoriza al usuario a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación que se aplicarán a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     *  - `nombre`: Obligatorio, string, máximo 255 caracteres, único en la tabla `events`
     *  ignorando el evento actual (`$eventoId`).
     *  - Los demás campos siguen lógicas similares de obligatoriedad y validación de formato.
     */
    public function rules(): array
    {
        $eventoId = $this->route('id');

        return [
            'nombre'        => "required|string|max:255|unique:events,name,{$eventoId}",
            'encargado'     => 'required|string|max:255',
            'sala_id'       => 'required|exists:rooms,id',
            'fecha'         => 'required|date',
            'hora_inicio'   => 'required|date_format:H:i',
            'hora_fin'      => 'required|date_format:H:i|after:hora_inicio',
            'estado_id'     => 'required|exists:state_events,id',
        ];
    }

    /**
     * Define los mensajes de error personalizados para las reglas de validación.
     *
     * @return array<string, string>
     *  - Asocia cada regla de validación con un mensaje en español más descriptivo.
     */
    public function messages(): array
    {
        return [
            'nombre.required'        => 'El nombre del evento es obligatorio.',
            'nombre.string'          => 'El nombre del evento debe ser texto.',
            'nombre.max'             => 'El nombre del evento no puede superar los 255 caracteres.',
            'nombre.unique'          => 'El nombre del evento ya existe.',

            'encargado.required'     => 'El encargado del evento es obligatorio.',
            'encargado.string'       => 'El encargado del evento debe ser texto.',
            'encargado.max'          => 'El nombre del encargado no puede superar los 255 caracteres.',

            'sala_id.required'       => 'La sala es obligatoria.',
            'sala_id.exists'         => 'La sala seleccionada no existe.',

            'fecha.required'         => 'La fecha es obligatoria.',
            'fecha.date'             => 'La fecha debe ser válida.',

            'hora_inicio.required'   => 'La hora de inicio es obligatoria.',
            'hora_inicio.date_format'=> 'La hora de inicio debe tener formato HH:MM.',

            'hora_fin.required'      => 'La hora de fin es obligatoria.',
            'hora_fin.date_format'   => 'La hora de fin debe tener formato HH:MM.',
            'hora_fin.after'         => 'La hora de fin debe ser mayor a la hora de inicio.',

            'estado_id.required'     => 'El estado del evento es obligatorio.',
            'estado_id.exists'       => 'El estado seleccionado no existe.',
        ];
    }

}
