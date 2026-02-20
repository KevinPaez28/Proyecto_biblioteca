<?php

namespace App\Http\Requests\Actions;

use Illuminate\Foundation\Http\FormRequest;

class createActions extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición.
     *
     * @return bool
     */
    /**
     */
    public function authorize(): bool
    {
        return true;
    }
    
    /**
     * Define las reglas de validación que se aplicarán a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    /**
     *
     */
    public function rules(): array
    {
        return [
            // El campo 'nombre' es obligatorio y debe ser una cadena de texto.
            "nombre"=> "required|string",
        ];
    }

    /**
     * Define los mensajes de error personalizados para las reglas de validación.
     *
     * @return array<string, string>
     */
    public function messages(): array  {
        return [  
            // Mensaje de error si el campo 'nombre' es obligatorio.
            "nombre.required"=> "El nombre es obligatorio",
            // Mensaje de error si el campo 'nombre' no es una cadena de texto.
            "nombre.string"=> "El nombre debe ser texto",
        ];
    }
}
