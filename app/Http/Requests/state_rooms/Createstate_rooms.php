<?php

namespace App\Http\Requests\state_rooms;

use Illuminate\Foundation\Http\FormRequest;

class Createstate_rooms extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

   
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:50'
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El campo debe ser texto.',
            'nombre.max' => 'El nombre no puede superar los 50 caracteres.',
        ];
    }
}
