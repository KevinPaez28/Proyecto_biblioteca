<?php

namespace App\Http\Requests\Actions;

use Illuminate\Foundation\Http\FormRequest;

class updateActions extends FormRequest
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
    { // Obtener el ID de la ficha desde la ruta /ficha/{id}
        $actions = $this->route('id');

        return [
            'nombre' => "required|string|max:20|unique:ficha,ficha,{$actions}",
        ];
    }
    public function messages(): array{
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string'=> 'El nombre deber ser texto',
        ];
    }
}
