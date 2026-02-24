<?php

namespace App\Http\Requests\History;

use Illuminate\Foundation\Http\FormRequest;

class createHistory extends FormRequest
{
    /**
     * Autoriza al usuario a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación para la solicitud.
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
   
    public function rules(): array
    {
        return [
            'usuario_id'=> 'required|integer|exists:users,id',
            'accion'=> 'required|integer|exists:actions,id',
            'descripcion'=> 'required|string',
            'tipo_modelo'=> 'required|string',
            'modelo_id'=> 'required|string', //Se cambio a string porque permite guardar valores alfanumericos
            'fecha_creacion'=> 'required|',
        ];
    }

    /**
     *  Define los mensajes de error personalizados para las reglas de validación.
     *
     * @return array<string, string>
     */
   
    public function messages(): array
    {
        return [
            'usuario_id.required'    => 'El campo usuario es obligatorio.',
            'usuario_id.integer'     => 'El campo usuario debe ser un número entero.',
            'usuario_id.exists'      => 'El usuario seleccionado no existe.',

            'accion.required'    => 'El campo acción es obligatorio.',
            'accion.integer'     => 'El campo acción debe ser un número entero.',
            'accion.exists'      => 'La acción seleccionada no existe.',

            'descripcion.required'   => 'La descripción es obligatoria.',
            'descripcion.string'     => 'La descripción debe ser un texto.',
            'descripcion.max'        => 'La descripción no puede tener más de 500 caracteres.',

            'tipo_modelo.required'    => 'El tipo de modelo es obligatorio.',
            'tipo_modelo.string'      => 'El tipo de modelo debe ser un texto.',
            'tipo_modelo.max'         => 'El tipo de modelo no puede tener más de 100 caracteres.',
            
            'modelo_id.required'    => 'El tipo de modelo es obligatorio.',
            'modelo_id.string'      => 'El ID del modelo debe ser un texto.',
            'modelo_id.max'         => 'El tipo de modelo no puede tener más de 100 caracteres.',

            'fecha_creacion.required' => 'La fecha de creación es obligatoria.',
            'fecha_creacion.date'     => 'La fecha de creación no es válida.',
        ];
    }
}
