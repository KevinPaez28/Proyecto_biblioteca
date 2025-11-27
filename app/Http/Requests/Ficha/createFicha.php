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
            'ficha' => 'required|string|unique:ficha,ficha',
            'programa' =>'required|numeric|exists:programs,id'
        ];
    }

    public function messages(): array{
        return [
            'ficha.required' => 'El ID de la ficha es obligatorio.',
            'ficha.string' => 'El ID de la ficha debe ser una cadena de texto.',
            'ficha.unique' => 'El ID de la ficha ya existe.',
            'programa.required' => 'El id del programa es obligatorio.',
            'programa.string' => 'El id del programa debe ser una cadena de texto.',
            'programa.exists' => 'El id del programa no existe en la tabla de programas.',
        ];
    }

}
