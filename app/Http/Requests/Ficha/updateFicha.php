<?php

namespace App\Http\Requests\Ficha;

use Illuminate\Foundation\Http\FormRequest;

class updateFicha extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        // Obtener el ID de la ficha desde la ruta /ficha/{id}
        $fichaId = $this->route('id');

        return [
            'ficha' => "required|string|max:20|unique:ficha,ficha,{$fichaId}",
            'programa' => 'required|exists:program,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ficha.required' => 'La ficha es obligatoria.',
            'ficha.string' => 'La ficha debe ser texto.',
            'ficha.max' => 'La ficha no puede superar los 20 caracteres.',
            'ficha.unique' => 'Esta ficha ya está registrada.',

            'programa.required' => 'El programa es obligatorio.',
            'programa.exists' => 'El programa seleccionado no existe.',
        ];
    }
}
