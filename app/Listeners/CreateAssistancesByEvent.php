<?php

namespace App\Listeners;

use App\Events\EventAssistanceCreated;
use App\Models\assitances\assitances;
use App\Models\Schedules\Schedules;
use App\Models\User\User;
use App\Models\Ficha_users\ficha_user;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CreateAssistancesByEvent
{
    use InteractsWithQueue;

    public function handle(EventAssistanceCreated $event): void
    {
        $eventId = $event->data['event_id'] ?? null;
        $fichaId = $event->data['ficha_id'] ?? null;

        // Validar datos básicos
        if (!$eventId || !$fichaId) {
            Log::warning("No se pudo crear asistencia: datos insuficientes.", [
                'event_id' => $eventId,
                'ficha_id' => $fichaId
            ]);
            return;
        }

        // Validar que el evento exista
        $evento = $event->data['event'] ?? null; // Si quieres puedes buscarlo en DB
        if (!$evento) {
            Log::warning("No se encontró el evento con ID: {$eventId}");
            return;
        }

        // Hora actual
        $horaActual = now()->format('H:i');

        // Buscar horario activo
        $horario = Schedules::where('start_time', '<=', $horaActual)
            ->where('end_time', '>=', $horaActual)
            ->first();

        if (!$horario) {
            Log::warning("No hay horario activo a la hora {$horaActual} para la ficha {$fichaId}");
            return;
        }

        // Obtener jornada (shift)
        $jornada = $horario->shifts()->first();
        if (!$jornada) {
            Log::warning("No se encontró jornada asociada al horario ID: {$horario->id}");
            return;
        }

        // Obtener IDs de usuarios de la ficha
        $usuariosIds = ficha_user::where('ficha_id', $fichaId)->pluck('usuario_id');

        if ($usuariosIds->isEmpty()) {
            Log::warning("La ficha {$fichaId} no tiene usuarios asignados. No se generará asistencia.");
            return;
        }

        // Traer los usuarios activos
        $usuarios = User::whereIn('id', $usuariosIds)->get();

        if ($usuarios->isEmpty()) {
            Log::warning("No se encontraron usuarios activos para la ficha {$fichaId}");
            return;
        }

        foreach ($usuarios as $usuario) {
            // Evitar duplicados el mismo día y misma jornada
            $existe = assitances::where('user_id', $usuario->id)
                ->where('working_day_id', $jornada->id)
                ->whereDate('created_at', today())
                ->exists();

            if ($existe) continue;

            // Registrar asistencia
            assitances::create([
                'user_id'        => $usuario->id,
                'working_day_id' => $jornada->id,
                'event_id'       => $eventId,
                'reason_id'      => 1, // Por defecto
            ]);
        }

        Log::info("Asistencias creadas correctamente para la ficha {$fichaId} y evento {$eventId}");
    }
}
