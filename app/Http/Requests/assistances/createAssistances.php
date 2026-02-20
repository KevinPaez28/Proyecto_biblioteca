<?php

namespace App\Http\Requests\assistances;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class createAssistances
 *
 * Request encargado de validar los datos necesarios
 * para la creación de un registro de asistencias.
 *
 * Aquí SOLO se valida, no se guarda nada.
 */
class createAssistances extends FormRequest
{
    /**
     * Autoriza si el usuario puede realizar esta petición.
     *
     * En este caso siempre retorna true, por lo que
     * cualquier usuario autenticado puede usarla.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para crear una asistencia.
     *
     * Se valida:
     * - Que el usuario exista
     * - Que la jornada sea válida
     * - Que el motivo exista
     * - Que el evento (si se envía) exista
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Documento del usuario, debe existir en la tabla users
            'usuario_id' => 'required|exists:users,document',

            // Jornada laboral (opcional pero validada si se envía)
            'jornada' => 'exists:shifts,id',

            // Motivo de la asistencia (obligatorio)
            'motivo' => 'required|exists:reasons,id',

            // Evento asociado (opcional)
            'evento' => 'nullable|exists:events,id',
        ];
    }

    /**
     * Mensajes personalizados para los errores de validación.
     *
     * Estos mensajes se retornan automáticamente cuando
     * alguna regla falla.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Usuario
            'usuario_id.required' => 'El usuario es obligatorio.',
            'usuario_id.exists'   => 'El usuario seleccionado no existe.',

            // Jornada
            'jornada.required' => 'La jornada laboral es obligatoria.',
            'jornada.exists'   => 'La jornada seleccionada no existe.',

            // Motivo
            'motivo.required' => 'El motivo es obligatorio.',
            'motivo.exists'   => 'El motivo seleccionado no existe.',

            // Evento
            'evento.exists' => 'El evento seleccionado no existe.',
        ];
    }
}
