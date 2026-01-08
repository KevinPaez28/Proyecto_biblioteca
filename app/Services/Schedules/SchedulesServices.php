<?php

namespace App\Services\Schedules;

use App\Models\Schedules\Schedules;
use App\Models\Shifts\Shifts;
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
    public function getJornadasAndHorarios($search = null)
    {
        $shifts = Shifts::all();

        if ($shifts->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay jornadas registradas",
                "data" => []
            ];
        }

        $data = [];
        $searchLower = $search ? strtolower($search) : null;

        foreach ($shifts as $shift) {

            $horario = Schedules::find($shift->schedules_id);

            if (!$horario) continue;

            // 🔍 FILTRO BACKEND
            if ($searchLower) {
                if (
                    !str_contains(strtolower($horario->name), $searchLower) &&
                    !str_contains(strtolower($shift->name), $searchLower)
                ) {
                    continue;
                }
            }

            $data[] = [
                'id'         => $shift->id,
                'jornada'    => $shift->name,
                'horario'    => $horario->name,
                'start_time' => date("g:i A", strtotime($horario->start_time)),
                'end_time'   => date("g:i A", strtotime($horario->end_time)),
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Jornadas y horarios obtenidos correctamente",
            "data" => $data
        ];
    }


    public function CreateSchedules(array $data)
    {
        $schedules = Schedules::Create([
            'start_time' => $data['hora_inicio'],
            'end_time' => $data['hora_fin'],
            'name' => $data['nombre'],
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
