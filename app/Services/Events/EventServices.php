<?php

namespace App\Services\Events;

use App\Models\Events\events;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class EventServices
{
    public function getEvents()
    {
        $rooms = events::all();

        if (count($rooms) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay eventos registrado",
                "data" => $rooms
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Eventos obtenidos con éxito",
            "data" => $rooms
        ];
    }
    public function gettoday()
    {
        $today = Carbon::today('America/Bogota');

        $events = events::whereDate('date', $today)->get();

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
        $rooms = events::Create([
            'name' => $data['nombre'],
            'mandated' => $data['encargado'],
            'room_id' => $data['sala_id'],
            'date' => $data['fecha'],
            'state_event_id' => $data['estado_id'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Eventos creado con éxito',
            'data' => $rooms,
        ];
    }

    public function updateEvents(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $rooms = events::find($id);

            if (!$rooms) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El evento no existe",
                ];
            }

            $rooms->update([
                'name' => $data['nombre'],
                'mandated' => $data['encargado'],
                'room_id' => $data['sala_id'],
                'date' => $data['fecha'],
                'state_event_id' => $data['estado_id'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "evento actualizado con éxito",
                "data" => $rooms
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
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
        $rooms = events::find($id);


        if (!$rooms)
            return [
                "error" => true,
                "code" => 404,
                "message" => "El evento no existe",
            ];

        $rooms->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Evento eliminado con éxito",
        ];
    }
}
