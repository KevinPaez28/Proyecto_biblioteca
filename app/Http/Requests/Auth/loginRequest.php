<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class loginRequest extends FormRequest
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
            'document' => 'required|string',
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'document.required' => 'El documento es obligatorio',
            'password.required' => 'La contraseña es obligatoria',

            'password.string' => 'La contraseña debe ser texto',
            'document.string' => 'El documento debe ser tener el formato correcto',
        ];
    }
}
