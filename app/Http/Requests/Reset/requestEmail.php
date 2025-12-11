<?php

namespace App\Http\Requests\Reset;

use Illuminate\Foundation\Http\FormRequest;

class requestEmail extends FormRequest
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
            'document' => 'required|exists:users,document',
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'document.required' => 'El documento es obligatorio.',
            'document.exists'   => 'El documento no existe en el sistema.',
        ];
    }
}
