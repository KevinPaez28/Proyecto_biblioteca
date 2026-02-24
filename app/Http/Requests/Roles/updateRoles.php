<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class updateRoles extends FormRequest
{
    /**
     * Autoriza al usuario a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación para la solicitud.
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleId = $this->route('id');

        return [
            'usuario_id' => 'required|integer|exists:users,id',
            'nombre' => "required|string|max:50|unique:roles,name,{$roleId}",
            'permiso' => 'nullable|string|max:50'
        ];
    }

    /**
     * Define los mensajes de error personalizados para las reglas de validación.
     * @return array<string, string>
     */
    public function messages(): array
    {
        // Mensajes de error personalizados
        return [
            'usuario_id.required' => 'El campo usuario es obligatorio.',
            'usuario_id.integer' => 'El ID de usuario debe ser un número entero.',
            'usuario_id.exists' => 'El usuario seleccionado no existe.',

            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
            'nombre.unique' => 'Ya existe un rol con ese nombre.',

            'permiso.string' => 'El guard permiso debe ser texto.',
            'permiso.max' => 'El permiso no puede superar los 50 caracteres.',
        ];
    }
}
