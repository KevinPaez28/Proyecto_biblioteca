<?php

namespace App\Services\Rooms;

use App\Models\Rooms\rooms;
use Exception;
use Illuminate\Support\Facades\DB;

class RoomServices
{
    public function getRooms(array $filters = [])
    {
        $query = DB::table('rooms as r')
            ->leftJoin('state_rooms as sr', 'r.state_room_id', '=', 'sr.id')
            ->select(
                'r.id',
                'r.name',
                'r.description',
                'sr.id as state_id',
                'sr.name as state_name'
            );

        // 🔍 FILTROS
        if (!empty($filters['nombre'])) {
            $query->where('r.name', 'like', '%' . $filters['nombre'] . '%');
        }

        if (!empty($filters['descripcion'])) {
            $query->where('r.description', 'like', '%' . $filters['descripcion'] . '%');
        }

        if (!empty($filters['estado'])) {
            $query->where('sr.id', $filters['estado']);
        }

        $data = $query->get();

        // 🧠 ARMAMOS LA ESTRUCTURA QUE EL FRONT YA USA
        $data = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'state' => [
                    'id' => $item->state_id,
                    'name' => $item->state_name,
                ]
            ];
        });

        return [
            "error" => false,
            "code" => 200,
            "message" => $data->isEmpty()
                ? "No hay salas registradas"
                : "Salas obtenidas con éxito",
            "data" => $data
        ];
    }





    public function Createrooms(array $data)
    {
        $rooms = rooms::Create([
            'name' => $data['nombre'],
            'description' => $data['descripcion'],
            'state_room_id' => $data['estado_id'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Sala creada con éxito',
            'data' => $rooms,
        ];
    }

    public function updaterooms(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $rooms = rooms::find($id);

            if (!$rooms) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "La sala no existe",
                ];
            }

            $rooms->update([
                'name' => $data['nombre'],
                'description' => $data['descripcion'],
                'state_room_id' => $data['estado_sala'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Sala actualizado con éxito",
                "data" => $rooms
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar la sala",
            ];
        }
    }

    public function deleterooms($id)
    {
        $room = Rooms::withCount('events')->find($id);

        if (!$room) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "La sala no existe",
            ];
        }

        // 🔥 Si tiene eventos asociados, NO se elimina
        if ($room->events_count > 0) {
            return [
                "error" => true,
                "code" => 400,
                "message" => "No se puede eliminar la sala porque tiene eventos asociados",
            ];
        }

        $room->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Sala eliminada con éxito",
        ];
    }
}
