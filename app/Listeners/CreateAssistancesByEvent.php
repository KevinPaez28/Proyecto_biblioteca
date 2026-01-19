<?php

namespace App\Listeners;

use App\Events\EventAssistanceCreated;
use App\Models\assitances\assitances;
use App\Models\Schedules\Schedules;
use App\Models\User\User;
use App\Models\Ficha_users\ficha_user;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateAssistancesByEvent
{
    use InteractsWithQueue;

    public function handle(EventAssistanceCreated $event): void
    {
        $eventId = $event->data['event_id'] ?? null;
        $fichaId = $event->data['ficha_id'] ?? null;

        if (!$eventId || !$fichaId) {
            return; // Datos insuficientes
        }

        // Hora actual
        $horaActual = now()->format('H:i');

        // Buscar horario activo
        $horario = Schedules::where('start_time', '<=', $horaActual)
            ->where('end_time', '>=', $horaActual)
            ->first();

        if (!$horario) return;

        // Obtener jornada (shift)
        $jornada = $horario->shifts()->first();
        if (!$jornada) return;

        // 🔹 Obtener IDs de usuarios de la ficha desde la tabla pivote directamente
        $usuariosIds = ficha_user::where('ficha_id', $fichaId)->pluck('usuario_id');

        if ($usuariosIds->isEmpty()) return;

        // Traer los usuarios activos (sin tocar relación fichas)
        $usuarios = User::whereIn('id', $usuariosIds)->get();

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
            ]);
        }
    }
}
