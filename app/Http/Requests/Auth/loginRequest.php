<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class loginRequest extends FormRequest
{
    /**
     *  Determina si el usuario está autorizado a realizar esta petición.
     *
     *  @return bool
     */
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     *  Obtiene las reglas de validación que se aplicarán a la petición.
     *
     *  @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document' => 'required|string',
            'password' => 'required|string',
        ];
    }

    /**
     *  Obtiene los mensajes de error personalizados para las reglas de validación.
     *
     *  @return array<string, string>
     */
    public function messages()
    {
        return [
            'document.required' => 'El documento es obligatorio', // Mensaje si el documento es obligatorio
            'password.required' => 'La contraseña es obligatoria', // Mensaje si la contraseña es obligatoria

            'password.string' => 'La contraseña debe ser texto', // Mensaje si la contraseña debe ser texto
            'document.string' => 'El documento debe ser tener el formato correcto', // Mensaje si el documento debe tener el formato correcto
        ];
    }
}
