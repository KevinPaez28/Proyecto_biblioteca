<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;

class updateEvent extends FormRequest
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
        $eventoId = $this->route('id');

        return [
            'nombre' => "required|string|max:255|unique:events,name,{$eventoId}",
            'encargado' => 'required|string|max:255',
            'sala_id' => 'required|exists:rooms,id',
            'fecha' => 'required|date',
            'estado_id' => 'required|exists:state_events,id',
        ];
    }

    /**
     * Mensajes personalizados en español.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del evento es obligatorio.',
            'nombre.string' => 'El nombre del evento debe ser texto.',
            'nombre.max' => 'El nombre del evento no puede superar los 255 caracteres.',
            'nombre.unique' => 'El nombre del evento ya existe.',
            'encargado.required' => 'El encargado del evento es obligatorio.',
            'encargado.string' => 'El encargado del evento debe ser texto.',
            'encargado.max' => 'El nombre del encargado no puede superar los 255 caracteres.',
            'sala_id.required' => 'La sala es obligatoria.',
            'sala_id.exists' => 'La sala seleccionada no existe.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe ser válida.',
            'estado_id.required' => 'El estado del evento es obligatorio.',
            'estado_id.exists' => 'El estado seleccionado no existe.',
        ];
    }
}