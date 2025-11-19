<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'documento' => 'required|string|unique:users,document|min:8|max:10',
            'contrasena' => 'required|string|min:8',
            'estados_id' => 'required|exists:user_statuses,id',
        ];
    }

    public function messages(): array
    {
        return [
            'documento.required' => 'El documento es obligatorio.',
            'documento.unique' => 'El documento ya existe.',
            'documento.min' => 'El documento debe tener mínimo 8 caracteres.',
            'documento.max' => 'El documento no puede tener más de 10 caracteres.',
            'contrasena.required' => 'La contraseña es obligatoria.',
            'contrasena.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'estados_id.required' => 'El estado es obligatorio.',
            'estados_id.exists' => 'El estado seleccionado no existe.',
        ];
    }
    public function attributes(): array
    {
        return [
            'document' => 'número de documento',
            'password' => 'contrasena',
            'status_id' => 'Estado'
             ];
    }
}
