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
                'e.time',
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
                'time'     => Carbon::parse($item->time)->format('g:i A'),
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
                    'time' => Carbon::parse($event->time)->format('g:i A'), // 👈 FORMATO 12 HORAS
                    'room_id' => $event->room_id,
                    'state_event_id' => $event->state_event_id,
                ];
            });

        if ($events->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay eventos registrados hoy",
                "data" => $events
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Eventos de hoy obtenidos con éxito",
            "data" => $events
        ];
    }


    public function CreateEvents(array $data)
    {
        // Revisar si ya hay un evento en la misma sala y hora
        $existe = events::where('room_id', $data['sala_id'])
            ->where('date', $data['fecha'])
            ->where('time', $data['hora'])
            ->first();

        if ($existe) {
            return [
                'error' => true,
                'code' => 409, // conflicto
                'message' => 'Ya existe un evento en esta sala a esa hora.',
            ];
        }

        // Crear evento si no hay conflicto
        $event = events::create([
            'name' => $data['nombre'],
            'mandated' => $data['encargado'],
            'room_id' => $data['sala_id'],
            'date' => $data['fecha'],
            'time' => $data['hora'],
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

            $event->update([
                'name' => $data['nombre'],
                'mandated' => $data['encargado'],
                'room_id' => $data['sala_id'],
                'date' => $data['fecha'],
                'time' => $data['hora'],
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
        $event = events::find($id);

        if (!$event) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "El evento no existe",
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
