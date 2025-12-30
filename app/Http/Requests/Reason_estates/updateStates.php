<?php

namespace App\Http\Requests\Reason_estates;

use Illuminate\Foundation\Http\FormRequest;

class updateStates extends FormRequest
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
        $id = $this->route('id');

        return [
            'nombre' => "required|string|max:50|unique:states_Reason,name,{$id}",
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El campo debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
            'nombre.unique' => 'El nombre ya existe.',
        ];
    }
}
