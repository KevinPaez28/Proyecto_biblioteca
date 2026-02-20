<?php

namespace App\Http\Requests\Actions;

use Illuminate\Foundation\Http\FormRequest;

class updateActions extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición.
     *
     * @return bool
     */
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    { // Obtener el ID de la ficha desde la ruta /ficha/{id}
        // Obtiene el ID de la acción desde la ruta.
        $actions = $this->route('id');

        return [
            // El campo 'nombre' es obligatorio, debe ser una cadena de texto,
            // no debe exceder los 20 caracteres y debe ser único en la tabla 'ficha'
            // excluyendo el registro con el ID actual.
            'nombre' => "required|string|max:20|unique:ficha,ficha,{$actions}",
        ];
    }

    /**
     * Obtiene los mensajes de error para las reglas de validación definidas.
     * @return array<string, string>
     */
    public function messages(): array {
        return [
            'nombre.required' => 'El nombre es obligatorio.', // Mensaje si el nombre es obligatorio.
            'nombre.string'=> 'El nombre deber ser texto',
        ];
    }
}
