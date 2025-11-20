<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class updateRequest extends FormRequest
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
        $userId = $this->route('id');

        return [
            'documento' => "required|string|max:10|unique:users,document,{$userId}",
            'estado_id' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'documento.required' => 'El documento es obligatorio.',
            'documento.string' => 'El documento debe ser texto.',
            'documento.max' => 'El documento no puede superar los 10 caracteres.',
            'documento.unique' => 'Este documento ya se encuentra registrado.',

            'contrasena.min' => 'La contraseña debe tener mínimo 8 caracteres.',

            'estado_id.required' => 'El estado es obligatorio.',
            'estado_id.integer' => 'El estado debe ser un número.',
        ];
    }
}
