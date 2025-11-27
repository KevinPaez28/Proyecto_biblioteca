<?php

namespace App\Services\Rooms;

use App\Models\Rooms\rooms;
use Exception;
use Illuminate\Support\Facades\DB;

class RoomServices
{
    public function getrooms()
    {
        $rooms = rooms::all();

        if (count($rooms) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay salas registradas",
                "data" => $rooms
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Salas obtenidas con éxito",
            "data" => $rooms
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
                'state_room_id' => $data['estado_id'],
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
        $rooms = rooms::find($id);


        if (!$rooms)
            return [
                "error" => true,
                "code" => 404,
                "message" => "La sala no existe",
            ];

        $rooms->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Sala eliminada con éxito",
        ];
    }
}
    