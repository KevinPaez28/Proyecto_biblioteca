<?php

namespace App\Http\Requests\History;

use Illuminate\Foundation\Http\FormRequest;

class updateHistory extends FormRequest
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
        
        $historyId = $this->route('id');

        return [
            'usuario_id'    => "required|integer|exists:users,{$historyId}",
            'accion'     => "required|integer|exists:actions,id",
            'descripcion'   => "required|string|",
            'modelo_id'     => "required|integer",
            'tipo_modelo'   => "required|string|",
            'fecha_creacion' => "required|date",
        ];
    }

    /**
     * Mensajes de validación personalizados en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'usuario_id.required'     => 'El campo usuario es obligatorio.',
            'usuario_id.integer'      => 'El campo usuario debe ser un número entero.',
            'usuario_id.exists'       => 'El usuario seleccionado no existe.',

            'accion.required'      => 'El campo acción es obligatorio.',
            'accion.integer'       => 'El campo acción debe ser un número entero.',
            'accion.exists'        => 'La acción seleccionada no existe.',

            'descripcion.required'    => 'La descripción es obligatoria.',
            'descripcion.string'      => 'La descripción debe ser un texto.',

            'modelo_id.required'      => 'El campo modelo es obligatorio.',
            'modelo_id.integer'       => 'El campo modelo debe ser un número entero.',

            'tipo_modelo.required'    => 'El tipo de modelo es obligatorio.',
            'tipo_modelo.string'      => 'El tipo de modelo debe ser un texto.',

            'fecha_creacion.required' => 'La fecha de creación es obligatoria.',
            'fecha_creacion.date'     => 'La fecha de creación no es válida.',
        ];
    }
}
