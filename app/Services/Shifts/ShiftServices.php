<?php

namespace App\Services\Shifts;

use App\Models\Shifts\Shifts;
use Exception;
use Illuminate\Support\Facades\DB;

class ShiftServices
{
    public function getShifts()
    {
        $Shifts = Shifts::all();

        if (count($Shifts) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay jornadas registradas",
                "data" => $Shifts
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Jornadas obtenidos con éxito",
            "data" => $Shifts
        ];
    }
    public function CreateShifts(array $data)
    {
        $Shifts = Shifts::Create(attributes: [
            'name' => $data['nombreGJU¿'],
            'schedules_id' => $data['horario_id'],

        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Jornadas creada con éxito',
            'data' => $Shifts,
        ];
    }

    public function updateShifts(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $Shifts = Shifts::find($id);

            if (!$Shifts) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "La jornada no existe",
                ];
            }

            $Shifts->update([
                'nombre' => $data['name'],
                'schedules_id' => $data['horario_id'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Jornadas actualizadas con éxito",
                "data" => $Shifts
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar las jornadas",
            ];
        }
    }

    public function deleteShifts($id)
    {
        $Shifts = Shifts::find($id);


        if (!$Shifts)
            return [
                "error" => true,
                "code" => 404,
                "message" => "Las Jornadas no existe",
            ];

        $Shifts->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Jornadas eliminado con éxito",
        ];
    }
}
