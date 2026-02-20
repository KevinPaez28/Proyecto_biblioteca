<?php

namespace App\Http\Requests\assistances;

use Illuminate\Foundation\Http\FormRequest;

class createAssistances extends FormRequest
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
        return [
            'usuario_id' => 'required|exists:users,document',
            'jornada' => 'exists:shifts,id',
            'motivo'=> 'required|exists:reasons,id',
            'evento' => 'nullable|exists:events,id',
        ];
    }

    public function messages(): array
    {
        return [
            'usuario_id.required'        => 'El usuario es obligatorio.',
            'usuario_id.exists'          => 'El usuario seleccionado no existe.',

            'jornada.required' => 'La jornada laboral es obligatoria.',
            'jornada.exists'   => 'La jornada seleccionada no existe.',

            'motivo.required'      => 'El motivo es obligatorio.',
            'motivo.exists'        => 'El motivo seleccionado no existe.',

            'evento.exists'         => 'El evento seleccionado no existe.',
        ];
    }
}
