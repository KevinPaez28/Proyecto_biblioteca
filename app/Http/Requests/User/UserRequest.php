<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'documento' => 'required|string|min:8|max:10|unique:users,document',
            'contrasena' => 'nullable|string|min:8', 
            'estados_id' => 'exists:user_statuses,id',

            'nombres'   => 'required|string|max:15',
            'apellidos' => 'required|string|max:15',
            'rol'  => 'required|exists:roles,id',
            'ficha_id'  => 'nullable|exists:ficha,id',
            'programa'  => 'nullable|exists:programs,id',
            'correo'    => 'required|email|max:50|unique:users,email',
            'telefono'  => 'required|string|max:10|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            // Documento
            'documento.required' => 'El documento es obligatorio.',
            'documento.string'   => 'El documento debe ser un texto.',
            'documento.min'      => 'El documento debe tener al menos 8 caracteres.',
            'documento.max'      => 'El documento no puede tener más de 10 caracteres.',
            'documento.unique'   => 'El documento ya está registrado.',

            // Contraseña
            'contrasena.string'  => 'La contraseña debe ser un texto.',
            'contrasena.min'     => 'La contraseña debe tener al menos 8 caracteres.',

            // Estado
            'estados_id.exists'  => 'El estado seleccionado no existe.',

            // Nombres y apellidos
            'nombres.required'   => 'Los nombres son obligatorios.',
            'nombres.string'     => 'Los nombres deben ser un texto.',
            'nombres.max'        => 'Los nombres no pueden superar los 15 caracteres.',

            'apellidos.required' => 'Los apellidos son obligatorios.',
            'apellidos.string'   => 'Los apellidos deben ser un texto.',
            'apellidos.max'      => 'Los apellidos no pueden superar los 15 caracteres.',

            // Rol
            'rol.required'  => 'El rol es obligatorio.',
            'rol.exists'    => 'El rol seleccionado no existe.',

            // Ficha y programa
            'ficha_id.exists'    => 'La ficha seleccionada no existe.',
            'programa.exists'    => 'El programa seleccionado no existe.',

            // Correo
            'correo.required'    => 'El correo es obligatorio.',
            'correo.email'       => 'El correo no es válido.',
            'correo.max'         => 'El correo no puede superar los 50 caracteres.',
            'correo.unique'      => 'El correo ya está registrado.',

            // Teléfono
            'telefono.required'  => 'El teléfono es obligatorio.',
            'telefono.string'    => 'El teléfono debe ser un texto.',
            'telefono.max'       => 'El teléfono no puede superar los 10 caracteres.',
            'telefono.min'       => 'El teléfono no puede ser menor a los 10 caracteres.',
        ];
    }
}
