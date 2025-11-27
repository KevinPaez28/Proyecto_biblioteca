<?php

namespace App\Http\Requests\Programa;

use Illuminate\Foundation\Http\FormRequest;

class createProgram extends FormRequest
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
            'programa_formacion' => 'unique:programs,training_program|required|string|max:50'
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'programa_formacion.required' => 'El nombre del programa es obligatorio.',
            'programa_formacion.string' => 'El campo debe ser texto.',
            'programa_formacion.max' => 'El nombre del programa no puede superar los 50 caracteres.',
            'programa_formacion.unique' => 'El nombre del programa ya existe.',
        ];
    }
}
