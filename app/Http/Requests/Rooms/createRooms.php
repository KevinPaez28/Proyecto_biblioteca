<?php

namespace App\Http\Requests\Rooms;

use Illuminate\Foundation\Http\FormRequest;

class createRooms extends FormRequest
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
            'nombre'        => 'required|string',
            'descripcion'   => 'required|string',
            'estado_id'   => 'required|exists:state_rooms,id',
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required'        => 'El nombre es obligatorio.',
            'nombre.string'          => 'El nombre debe ser un texto válido.',

            'descripcion.required'   => 'La descripción es obligatoria.',
            'descripcion.string'     => 'La descripción debe ser un texto válido.',

            'estado_id.required'   => 'El estado de la sala es obligatorio.',
            'estado_id.exists'     => 'El estado seleccionado no es válido.',
        ];
    }
}
