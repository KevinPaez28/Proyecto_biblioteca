<?php

namespace App\Http\Requests\Reset;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'current_password' => ['required'],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'new_password.confirmed' => 'Las contraseñas no coinciden',
            'new_password.min' => 'La nueva contraseña debe tener mínimo 8 caracteres',
        ];
    }
}
