<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class createDocument extends FormRequest
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
    public function rules()
    {
        return [
            'users_id'  => 'required|exists:users,id', 
            'titulo'    => 'required|string',
            'ruta'      => 'nullable|string', 
            'extension' => 'nullable|string', 
            'tipo'      => 'required|string',
            'archivo'   => 'nullable|file|mimes:xlsx,xls|max:2048', 
            'tamano'    => 'nullable|integer',  
        ];
    }

    /**
     * Custom messages
     */
    public function messages()
    {
        return [
            'users_id.required'  => 'El usuario es obligatorio.',
            'users_id.exists'    => 'El usuario seleccionado no existe.',
            'titulo.required'    => 'El título es obligatorio.',
            'titulo.string'      => 'El título debe ser un texto.',
            'ruta.string'        => 'La ruta debe ser un texto.',
            'extension.string'   => 'La extensión debe ser un texto.',
            'tipo.required'      => 'El tipo de documento es obligatorio.',
            'tipo.string'        => 'El tipo de documento debe ser un texto.',
            'archivo.file'       => 'Debe ser un archivo válido.',
            'archivo.mimes'      => 'El archivo debe ser xlsx, xls, pdf, doc o docx.',
            'archivo.max'        => 'El archivo no puede superar 2MB.',
            'tamano.integer'     => 'El tamaño debe ser un número entero.',
        ];
    }
}
