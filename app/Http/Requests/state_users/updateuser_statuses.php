<?php

namespace App\Http\Requests\state_users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class updateuser_statuses extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     *
     * Por defecto, está establecido en `false`. Deberías revisar y modificar
     * esto según tus necesidades de autorización.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Define las reglas de validación que se aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Obtiene el ID del estado de usuario de la ruta.
        $id = $this->route('id');

        return [
            // 'nombre' es obligatorio, debe ser una cadena de texto, no debe exceder los 50 caracteres,
            // y debe ser único en la tabla 'states_Reason' ignorando el registro actual.
            'nombre' => ['required', 'string', 'max:50', Rule::unique('state_users', 'name')->ignore($id)],
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El campo debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
            'nombre.unique' => 'El nombre ya existe.',
        ];
    }
}
