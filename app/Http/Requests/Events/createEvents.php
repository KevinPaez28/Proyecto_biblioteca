<?php

namespace App\Http\Requests\Reason_estates;

use Illuminate\Foundation\Http\FormRequest;

class createEvents extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre'        => 'required|string|max:255',
            'encargado'     => 'required|string|max:255',
            'sala_id'       => 'required|exists:rooms,id',
            'fecha'         => 'required|date',
            'estado_evento' => 'required|exists:state_events,id',
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required'        => 'El nombre es obligatorio.',
            'nombre.string'          => 'El nombre debe ser un texto válido.',
            'nombre.max'             => 'El nombre no puede superar los 255 caracteres.',

            'encargado.required'     => 'El encargado es obligatorio.',
            'encargado.string'       => 'El encargado debe ser un texto válido.',
            'encargado.max'          => 'El encargado no puede superar los 255 caracteres.',

            'sala_id.required'       => 'La sala es obligatoria.',
            'sala_id.exists'         => 'La sala seleccionada no es válida.',

            'fecha.required'         => 'La fecha es obligatoria.',
            'fecha.date'             => 'La fecha debe tener un formato válido.',

            'estado_evento.required' => 'El estado del evento es obligatorio.',
            'estado_evento.exists'   => 'El estado del evento seleccionado no es válido.'
        ];
    }
}
