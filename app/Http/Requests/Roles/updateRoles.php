<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;

class updateRoles extends FormRequest
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
        $roleId = $this->route('id');

        return [
            'usuario_id' => 'required|integer|exists:users,id',
            'nombre' => "required|string|max:50|unique:roles,name,{$roleId}",
            'permiso' => 'string|max:50'
        ];
    }

    /**
     * Mensajes personalizados para errores de validación.
     */
    public function messages(): array
    {
        return [
            'usuario_id.required' => 'El campo usuario es obligatorio.',
            'usuario_id.integer' => 'El ID de usuario debe ser un número entero.',
            'usuario_id.exists' => 'El usuario seleccionado no existe.',

            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
            'nombre.unique' => 'Ya existe un rol con ese nombre.',

            'permiso.required' => 'El guard permiso es obligatorio.',
            'permiso.string' => 'El guard permiso debe ser texto.',
            'permiso.max' => 'El guard permiso no puede superar los 50 caracteres.',
        ];
    }
}
