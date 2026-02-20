<?php

namespace App\Http\Requests\assistances;

use Illuminate\Foundation\Http\FormRequest;

class createEventAssistance extends FormRequest
{
    /**
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'ficha_id' => 'required|exists:ficha,id',
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'El evento es obligatorio',
            'event_id.exists'   => 'El evento no existe',

            'ficha_id.required' => 'La ficha es obligatoria',
            'ficha_id.exists'   => 'La ficha no existe',
        ];
    }
}
