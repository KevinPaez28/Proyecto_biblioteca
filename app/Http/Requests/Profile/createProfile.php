<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class createProfile extends FormRequest
{
    /**
     * Autoriza al usuario a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Define las reglas de validación que se aplicarán a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'usuario'   => 'required|integer|exists:users,id',
            'nombre'    => 'required|string|max:100',
            'apellido'  => 'required|string|max:100',
            'telefono'   => 'string|max:10',
            'correo'    => 'required|email|max:255|unique:profiles,email',
            'programa'  => 'required|integer|exists:ficha,id',
        ];
    }

    /**
     * Personaliza los mensajes de error para las reglas de validación.
     */
    public function messages(): array
    {
        return [
            'usuario.required'  => 'Debe seleccionar un usuario.',
            'usuario.exists'     => 'El usuario seleccionado no existe.',

            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',

            'telefono.required'  => 'El número de celular es obligatorio.',

            'correo.required'   => 'El correo es obligatorio.',
            'correo.email'      => 'Debe ingresar un correo válido.',
            'correo.unique'     => 'Este correo ya está registrado.',

            'programa.required' => 'Debe seleccionar un programa.',
            'programa.exists'   => 'El programa seleccionado no existe.',
        ];
    }

}
