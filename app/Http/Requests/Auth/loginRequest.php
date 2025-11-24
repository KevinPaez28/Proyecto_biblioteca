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
            'document' => 'required',
            'password' => 'required|string|min:8|max:20',
        ];
    }

    public function messages()
    {
        return [
            'document.required' => 'El :attribute es obligatorio',
            'password.required' => 'La :attribute es obligatoria',

            'password.min' => 'La :attribute debe tener al menos :min caracteres.',
            'password.max' => 'La :attribute no debe tener más de :max caracteres',

            'password.string' => 'La :attribute debe ser texto',
            'document.string' => 'El :attribute debe ser tener el formato correcto',
        ];
    }
}
