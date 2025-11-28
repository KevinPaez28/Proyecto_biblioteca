<?php

namespace App\Http\Requests\Reasons;

use Illuminate\Foundation\Http\FormRequest;

class updateReasons extends FormRequest
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
        $Id = $this->route('id'); // ID de la jornada que se actualiza

        return [
            'nombre' => "required|string|unique:reasons,nombre,{$Id}",
            'descripcion' => 'nullable|string|',
            'estados_id' => 'required|exists:states_Reason,id',
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El campo Nombre es obligatorio.',
            'nombre.string' => 'El campo Nombre debe ser un texto válido.',
            'nombre.max' => 'El campo Nombre no debe exceder los 255 caracteres.',
            'nombre.unique' => 'El Nombre ya está registrado.',

            'descripcion.string' => 'La Descripción debe ser un texto válido.',
            'descripcion.max' => 'La Descripción no debe exceder los 500 caracteres.',

            'estado_id.required' => 'El campo Estado es obligatorio.',
            'estado_id.exists' => 'El Estado seleccionado no es válido.',
        ];
    }
}
