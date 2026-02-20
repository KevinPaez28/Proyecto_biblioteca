<?php

namespace App\Http\Requests\email;

use Illuminate\Foundation\Http\FormRequest;

class ResendVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    /**

     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'El :attribute es obligatorio',
            'email.email' => 'El :attribute debe tener formato válido'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email' => 'correo',
        ];
    }
}
