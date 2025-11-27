<?php

namespace App\Http\Requests\state_events;

use Illuminate\Foundation\Http\FormRequest;

class updateEventEstates extends FormRequest
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
        $Id = $this->route('id'); // ID de la jornada que se actualiza

        return [
            'nombre' => "required|string|min:3|unique:state_events,id,{$Id}",
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'        => 'El nombre es obligatorio.',
            'nombre.string'          => 'El nombre debe ser un texto válido.'
        ];
    }
}
