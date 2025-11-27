<?php

namespace App\Http\Requests\Rooms;

use Illuminate\Foundation\Http\FormRequest;

class updateRooms extends FormRequest
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
        $Id = $this->route('id'); // ID de la jornada que se actualiza

        return [
            'nombre' => "required|string|min:3|unique:rooms,nombre,{$Id}",
            'descripcion'   => 'required|string',
            'estado_sala'   => 'required|exists:state_rooms,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'        => 'El nombre es obligatorio.',
            'nombre.string'          => 'El nombre debe ser un texto válido.',

            'descripcion.required'   => 'La descripción es obligatoria.',
            'descripcion.string'     => 'La descripción debe ser un texto válido.',

            'estado_sala.required'   => 'El estado de la sala es obligatorio.',
            'estado_sala.exists'     => 'El estado seleccionado no es válido.',
        ];
    }
}
