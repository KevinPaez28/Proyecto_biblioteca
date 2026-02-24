<?php

namespace App\Http\Requests\Reset;

use Illuminate\Foundation\Http\FormRequest;

class Resetpassword extends FormRequest
{
   /**
     * Autoriza al usuario a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        // Autoriza al usuario a realizar la solicitud
        // Por defecto está en true, se puede agregar lógica
        // de autorización más compleja si es necesario.

        // En este caso, se permite a todos los usuarios
        // realizar esta solicitud.
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
            // 'token' es obligatorio
            'token'    => 'required',
            // 'email' debe ser un email válido y existir en la tabla 'users'
            'email'    => 'email|exists:users,email',
            // 'password' es obligatorio, debe tener al menos 8 caracteres y ser confirmado
            'password' => 'required|min:8|confirmed',
        ];
    }

    /**
     *  Define los mensajes de error personalizados para las reglas de validación.
     */
    public function messages(): array
    {
        //  Define los mensajes de error personalizados.
        return [
            'token.required' => 'El token es obligatorio.',
            'email.email'    => 'Debe ingresar un correo válido.',
            'email.exists'   => 'El correo no existe en el sistema.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener mínimo 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
