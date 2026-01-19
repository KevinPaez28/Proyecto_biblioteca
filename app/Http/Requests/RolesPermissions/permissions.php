<?php

namespace App\Http\Requests\RolesPermissions;

use Illuminate\Foundation\Http\FormRequest;

class permissions extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Cambia a true si quieres que cualquier usuario autorizado pueda hacer esto
        return true;
    }

    /**
     * Reglas de validación
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',          // nombre del permiso
            'permisos' => 'nullable|array',              // array de IDs de permisos
            'permisos.*' => 'integer|exists:permissions,id', // cada elemento debe existir en tabla permissions
        ];
    }

    /**
     * Mensajes personalizados (opcional)
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del rol es obligatorio.',
            'permisos.array' => 'Los permisos deben enviarse como un arreglo.',
            'permisos.*.integer' => 'Cada permiso debe ser un número válido.',
            'permisos.*.exists' => 'El permiso seleccionado no existe.',
        ];
    }
}