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
        $schedules = Schedules::all();

        if ($schedules->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay horarios registrados",
                "data" => []
            ];
        }

        $data = [];
        $searchLower = $search ? strtolower($search) : null;

        foreach ($schedules as $schedule) {

            // Buscar si existe una jornada asociada a este horario
            $shift = Shifts::where('schedules_id', $schedule->id)->first();

            $jornadaNombre = $shift ? $shift->name : "Sin jornada";

            // 🔍 FILTRO BACKEND
            if ($searchLower) {
                if (
                    !str_contains(strtolower($schedule->name), $searchLower) &&
                    !str_contains(strtolower($jornadaNombre), $searchLower)
                ) {
                    continue;
                }
            }

            $data[] = [
                'id'         => $schedule->id,
                'jornada'    => $jornadaNombre,
                'horario'    => $schedule->name,
                'start_time' => date("g:i A", strtotime($schedule->start_time)),
                'end_time'   => date("g:i A", strtotime($schedule->end_time)),
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
        $schedule = Schedules::withCount('shifts')->find($id);

        // 🔎 1. Validar existencia
        if (!$schedule) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "El horario no existe",
            ];
        }

        // 🚫 2. Validar si tiene jornadas asociadas
        if ($schedule->shifts_count > 0) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar el horario porque tiene jornadas asociadas",
            ];
        }

        // 🗑 3. Eliminar si está libre
        $schedule->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Horario eliminado con éxito",
        ];
    }
}
