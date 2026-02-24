<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class updateProfile extends FormRequest
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
     */

    
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'usuario'   => "required|integer|exists:users,{$id}",
            'nombre'    => 'required|string|max:100', 
            'apellidos'  => 'required|string|max:100',
            'telefono'   => 'string|max:10',
            'correo'    => 'required|email|max:255|unique:profiles,email',
            'programa'  => 'required|integer|exists:ficha,id',
        ];
    }

    /**
     *  Define los mensajes de error personalizados para las reglas de validación.
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