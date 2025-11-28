<?php

namespace App\Http\Requests\Programa;

use Illuminate\Foundation\Http\FormRequest;

class updateProgram extends FormRequest
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
        $programId = $this->route('id');

        return [
            'programa_formacion' => "required|string|max:50|unique:programs,training_program,{$programId}",
        ];
    }

    /**
     * Mensajes personalizados en español.
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
