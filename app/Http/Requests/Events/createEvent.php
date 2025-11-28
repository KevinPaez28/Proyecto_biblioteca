<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;

class createEvent extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'encargado' => 'required|string|max:255',
            'sala_id' => 'required|exists:rooms,id',
            'fecha' => 'required|date',
            'estado_id' => 'required|exists:state_events,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del evento es obligatorio.',
            'encargado.required' => 'El encargado del evento es obligatorio.',
            'sala_id.required' => 'La sala es obligatoria.',
            'sala_id.exists' => 'La sala seleccionada no existe.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe ser válida.',
            'estado_id.required' => 'El estado del evento es obligatorio.',
            'estado_id.exists' => 'El estado seleccionado no existe.',
        ];
    }
}
