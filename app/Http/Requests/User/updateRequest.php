<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class updateRequest extends FormRequest
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
        $userId = $this->route('id');

        return [
            // USUARIO
            'documento' => "required|string|max:10|unique:users,document,{$userId}",
            'correo'    => 'required|email|max:255',
            'status_id' => 'required|integer|exists:user_statuses,id',
            'rol_id'    => 'required|integer|exists:roles,id',
            'ficha_id'     => 'required|integer|exists:ficha,id',
            'programa_id'  => 'required|integer|exists:programs,id',
            'nombres'   => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'telefono'  => 'required|string|min:10|max:10',
        ];
    }

    /**
     * Mensajes personalizados
     */
    public function messages(): array
    {
        return [
            'documento.required' => 'El documento es obligatorio.',
            'documento.unique'   => 'Este documento ya se encuentra registrado.',
            'documento.max'      => 'El documento no puede tener más de 10 caracteres.',

            'correo.required'    => 'El correo es obligatorio.',
            'correo.email'       => 'El correo no tiene un formato válido.',

            'status_id.required' => 'El estado es obligatorio.',
            'status_id.exists'   => 'El estado seleccionado no es válido.',

            'rol_id.required'    => 'El rol es obligatorio.',
            'rol_id.exists'      => 'El rol seleccionado no es válido.',

            'ficha_id.required'    => 'La ficha es obligatoria.',
            'ficha_id.exists'      => 'El ficha seleccionada no es válido.',

            'programa_id.required'    => 'El programa es obligatorio.',
            'programa_id.exists'      => 'El programa seleccionada no es válido.',

            'nombres.required'   => 'El nombre es obligatorio.',
            'apellidos.required' => 'El apellido es obligatorio.',
            'telefono.required'  => 'El teléfono es obligatorio.',
            'telefono.min'       => 'El teléfono debe tener 10 dígitos.',
            'telefono.max'       => 'El teléfono debe tener 10 dígitos.',
        ];
    }
}
