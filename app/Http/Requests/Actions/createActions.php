<?php

namespace App\Http\Requests\Actions;

use Illuminate\Foundation\Http\FormRequest;

class createActions extends FormRequest
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
            "nombre"=> "required|string",
        ];
    }

    public function messages(): array  {
        return [  
            "nombre.required"=> "El nombre es obligatorio",
            "nombre.string"=> "El nombre debe ser texto",
        ];
    }
}
