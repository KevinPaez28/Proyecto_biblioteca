<?php

namespace App\Services\assitances;

use App\Models\assitances\assitances;
use Exception;
use Illuminate\Support\Facades\DB;

class assitanceServices
{
    public function getAssistances()
    {
        $rooms = assitances::all();

        if (count($rooms) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay asistencias registradas",
                "data" => $rooms
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Asistencias obtenidas con éxito",
            "data" => $rooms
        ];
    }
    public function CreateAssistances(array $data)
    {
        echo implode(",", $data);
        $rooms = assitances::Create([
            'user_id' => $data['usuario_id'],
            'working_day_id' => $data['jornada'],
            'reason_id' => $data['motivo'],
            'event_id' => $data['evento'],
        ]);
        
        return [
            'error' => false,
            'code' => 201,
            'message' => 'Asistencia creada con éxito',
            'data' => $data,
        ];
    }

    public function updateAssistances(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $rooms = assitances::find($id);

            if (!$rooms) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "La asistencia no existe",
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
                "message" => "Asistencia actualizada con éxito",
                "data" => $rooms
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar la asistencia",
            ];
        }
    }

    public function deleteAssistances($id)
    {
        $rooms = assitances::find($id);


        if (!$rooms)
            return [
                "error" => true,
                "code" => 404,
                "message" => "La asistencia no existe",
            ];

        $rooms->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Asistencia eliminada con éxito",
        ];
    }
}
