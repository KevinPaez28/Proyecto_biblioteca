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
            'contrasena' => 'string|min:8',
            'estados_id' => 'required|exists:user_statuses,id',

            'nombres'     => 'required|string|max:255',
            'apellidos'   => 'required|string|max:255',
            'rol_sena'    => 'required|exists:roles,id',
            'ficha_id'       => 'nullable|exists:ficha,id',
            'programa'    => 'nullable|exists:programs,id',
            'correo'      => 'required|email|max:255',
            'telefono'    => 'required|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'documento.required' => 'El documento es obligatorio.',
            'documento.min' => 'El documento debe tener mínimo 8 caracteres.',
            'documento.max' => 'El documento no puede tener más de 10 caracteres.',
            'documento.unique' => 'El documento ya esta registrado',

            'contrasena.min'     => 'La contraseña debe tener al menos 8 caracteres.',

            'estados_id.required' => 'El estado es obligatorio.',
            'estados_id.exists'  => 'El estado no existe.',
             
            'nombres.required'   => 'Los nombres son obligatorios.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'rol_sena.required'  => 'El rol es obligatorio.',
            'correo.required'    => 'El correo es obligatorio.',
            'correo.email'       => 'El correo no es válido.',
            'telefono.required'  => 'El teléfono es obligatorio.',
        ];
    }
}
