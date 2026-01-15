<?php

namespace App\Http\Requests\Ficha;

use Illuminate\Foundation\Http\FormRequest;

class createFicha extends FormRequest
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
            'ficha' => 'required|unique:ficha,ficha', // ID de la ficha como texto
            'programa' => 'required|numeric|exists:programs,id' // ID del programa como número
        ];
    }

    public function messages(): array
    {
        return [
            'ficha.required' => 'El ID de la ficha es obligatorio.',
            'ficha.unique' => 'El ID de la ficha ya existe.',
            'programa.required' => 'El ID del programa es obligatorio.',
            'programa.numeric' => 'El ID del programa debe ser un número válido.',
            'programa.exists' => 'El ID del programa no existe en la tabla de programas.',
        ];
    }
}
