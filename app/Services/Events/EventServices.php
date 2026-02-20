<?php

namespace App\Services\Events;

use App\Models\Events\events;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class EventServices
{
    public function getEvents(array $filters = [])
    {
        $query = DB::table('events as e')
            ->leftJoin('rooms as r', 'e.room_id', '=', 'r.id')
            ->leftJoin('state_events as se', 'e.state_event_id', '=', 'se.id')
            ->select(
                'e.id',
                'e.name',
                'e.mandated',
                'e.date',
                'e.start_time',
                'e.end_time',
                'r.id as room_id',
                'r.name as room_name',
                'se.id as state_id',
                'se.name as state_name'
            );

        // ================= FILTROS =================
        if (!empty($filters['nombre'])) {
            $query->where('e.name', 'like', '%' . $filters['nombre'] . '%');
        }

        if (!empty($filters['estado'])) {
            $query->where('se.id', $filters['estado']);
        }

        if (!empty($filters['encargado'])) {
            $query->where('e.mandated', 'like', '%' . $filters['encargado'] . '%');
        }

        if (!empty($filters['fecha'])) {
            $query->whereDate('e.date', $filters['fecha']);
        }

        if (!empty($filters['sala'])) {
            $query->where('r.id', $filters['sala']);
        }

        $data = $query->get();

        // ================= FORMATEO =================
        $data = $data->map(function ($item) {
            return [
                'id'       => $item->id,
                'name'     => $item->name,
                'mandated' => $item->mandated,
                'date'     => $item->date,
                'time'     => [
                    'start' => $item->start_time
                        ? Carbon::parse($item->start_time)->format('g:i A')
                        : null,
                    'end'   => $item->end_time
                        ? Carbon::parse($item->end_time)->format('g:i A')
                        : null,
                ],
                'room'     => [
                    'id'   => $item->room_id,
                    'name' => $item->room_name,
                ],
                'state'    => [
                    'id'   => $item->state_id,
                    'name' => $item->state_name,
                ]
            ];
        });

        return [
            "error"   => false,
            "code"    => 200,
            "message" => $data->isEmpty()
                ? "No hay eventos registrados"
                : "Eventos obtenidos con éxito",
            "data"    => $data
        ];
    }

    public function gettoday()
    {
        $today = Carbon::today('America/Bogota');

        $events = events::whereDate('date', $today)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'name' => $event->name,
                    'mandated' => $event->mandated,
                    'date' => $event->date,
                    'start_time' => $event->start_time
                        ? Carbon::parse($event->start_time)->format('g:i A')
                        : null,
                    'end_time' => $event->end_time
                        ? Carbon::parse($event->end_time)->format('g:i A')
                        : null,
                    'room_id' => $event->room_id,
                    'state_event_id' => $event->state_event_id,
                ];
            });

        return [
            "error" => false,
            "code" => 200,
            "message" => $events->isEmpty()
                ? "No hay eventos registrados hoy"
                : "Eventos de hoy obtenidos con éxito",
            "data" => $events
        ];
    }

    // ================= CREATE (NO TOCADO) =================
    public function CreateEvents(array $data)
    {
        $existe = events::where('room_id', $data['sala_id'])
            ->where('date', $data['fecha'])
            ->where(function ($query) use ($data) {
                $query->where('start_time', '<', $data['hora_fin'])
                    ->where('end_time', '>', $data['hora_inicio']);
            })
            ->first();

        if ($existe) {
            return [
                'error' => true,
                'code' => 409,
                'message' => 'Ya existe un evento en esta sala dentro de ese rango de horas.',
            ];
        }

        $event = events::create([
            'name' => $data['nombre'],
            'mandated' => $data['encargado'],
            'room_id' => $data['sala_id'],
            'date' => $data['fecha'],
            'start_time' => $data['hora_inicio'],
            'end_time' => $data['hora_fin'],
            'state_event_id' => $data['estado_id'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Evento creado con éxito',
            'data' => $event,
        ];
    }

    public function updateEvents(array $data, $id)
    {
        try {
            DB::beginTransaction();

            $event = events::find($id);

            if (!$event) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El evento no existe",
                ];
            }

            // VALIDAR HORAS LÓGICAS
            if ($data['hora_inicio'] >= $data['hora_fin']) {
                return [
                    'error' => true,
                    'code' => 422,
                    'message' => 'La hora de inicio debe ser menor a la hora de fin.',
                ];
            }

            // VALIDAR CRUCE DE HORARIOS (EXCLUYENDO ESTE EVENTO)
            $existe = events::where('room_id', $data['sala_id'])
                ->where('date', $data['fecha'])
                ->where('id', '!=', $id)
                ->where(function ($query) use ($data) {
                    $query->where('start_time', '<', $data['hora_fin'])
                        ->where('end_time', '>', $data['hora_inicio']);
                })
                ->first();

            if ($existe) {
                return [
                    'error' => true,
                    'code' => 409,
                    'message' => 'Ya existe otro evento en esta sala dentro de ese rango de horas.',
                ];
            }

            $event->update([
                'name' => $data['nombre'],
                'mandated' => $data['encargado'],
                'room_id' => $data['sala_id'],
                'date' => $data['fecha'],
                'start_time' => $data['hora_inicio'],
                'end_time' => $data['hora_fin'],
                'state_event_id' => $data['estado_id'],
            ]);

            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Evento actualizado con éxito",
                "data" => $event
            ];
        } catch (Exception $e) {
            DB::rollback();

            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el evento",
            ];
        }
    }


    public function deleteEvents($id)
    {
        $event = Events::find($id);

        if (!$event) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "El evento no existe",
            ];
        }

        // 👇 validar relación
        if ($event->assistances()->exists()) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar el evento porque tiene asistencias registradas",
            ];
        }

        $event->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Evento eliminado con éxito",
        ];
    }
}
