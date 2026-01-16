<?php

namespace App\Listeners;

use App\Events\EventAssistanceCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class CreateAssistancesByEvent
{
    use InteractsWithQueue;

    public function handle(EventAssistanceCreated $event): void
    {
        $eventId = $event->data['event_id'];
        $fichaId = $event->data['ficha_id'];

        // Hora actual
        $horaActual = now()->format('H:i');

        // Horario activo
        $horario = Schedules::where('start_time', '<=', $horaActual)
            ->where('end_time', '>=', $horaActual)
            ->first();

        if (!$horario) return;

        $jornada = $horario->shifts()->first();
        if (!$jornada) return;

        // Usuarios de la ficha
        $usuarios = User::whereHas('perfil.fichas', function ($q) use ($fichaId) {
            $q->where('ficha_id', $fichaId);
        })->get();

        foreach ($usuarios as $usuario) {

            // Evitar duplicados el mismo día
            $existe = assitances::where('user_id', $usuario->id)
                ->where('working_day_id', $jornada->id)
                ->whereDate('created_at', today())
                ->exists();

            if ($existe) continue;

            assitances::create([
                'user_id'        => $usuario->id,
                'working_day_id' => $jornada->id,
                'event_id'       => $eventId,
            ]);
        }
    }
}
