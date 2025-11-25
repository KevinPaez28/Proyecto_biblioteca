<?php

namespace App\Services\Schedules;

use App\Models\Schedules\Schedules;
use Exception;
use Illuminate\Support\Facades\DB;

class SchedulesServices
{
    public function getSchedules()
    {
        $schedules = Schedules::all();

        if (count($schedules) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay horario registrado",
                "data" => $schedules
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Horarios obtenidos con éxito",
            "data" => $schedules
        ];
    }
    public function CreateSchedules(array $data)
    {
        $schedules = Schedules::Create([
            'start_time' => $data['hora_inicio'],
            'end_time' => $data['hora_fin'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Horario creado con éxito',
            'data' => $schedules,
        ];
    }

    public function updateSchedules(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $schedules = Schedules::find($id);

            if (!$schedules) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El horario no existe",
                ];
            }

            $schedules->update([
                'start_time' => $data['hora_inicio'],
                'end_time' => $data['hora_fin'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Horario actualizado con éxito",
                "data" => $schedules
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el horario",
            ];
        }
    }

    public function deleteSchedules($id)
    {
        $schedules = Schedules::find($id);


        if (!$schedules)
            return [
                "error" => true,
                "code" => 404,
                "message" => "El horario no existe",
            ];

        $schedules->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Horario eliminado con éxito",
        ];
    }
}
