<?php

namespace App\Services\assitances;

use App\Models\assitances\assitances;
use App\Models\Schedules\Schedules;
use App\Models\Shifts\Shifts;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // 1. Validar usuario
        $usuario = User::where('document', $data['usuario_id'])->first();
        if (!$usuario) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'El usuario con ese documento no existe.',
            ];
        }

        // 2. Hora actual
        $horaActual = now()->format('H:i');

        // 3. Buscar horario activo
        $horario = Schedules::where('start_time', '<=', $horaActual)
            ->where('end_time', '>=', $horaActual)
            ->first();

        if (!$horario) {
            return [
                'error' => true,
                'code' => 422,
                'message' => 'No hay ningún horario activo a esta hora',
            ];
        }

        // 4. Obtener jornada
        $jornada = $horario->shifts()->first();
        if (!$jornada) {
            return [
                'error' => true,
                'code' => 422,
                'message' => 'No hay ninguna jornada activa a esta hora',
            ];
        }

        // Verificar asistencia SOLO en la misma jornada DEL MISMO DÍA
        $existe = assitances::where('user_id', $usuario->id)
            ->where('working_day_id', $jornada->id)
            ->whereDate('created_at', today()) 
            ->exists();

        if ($existe) {
            return [
                'error' => true,
                'code' => 409,
                'message' => 'El usuario ya tiene una asistencia registrada en esta jornada hoy.',
            ];
        }

        // 6. Crear asistencia
        $asistencia = assitances::create([
            'user_id'        => $usuario->id,
            'working_day_id' => $jornada->id,
            'reason_id'      => $data['motivo'],
            'event_id'       => $data['evento'] ?? null,
        ]);

        return [
            'error'   => false,
            'code'    => 201,
            'message' => 'Asistencia creada con éxito',
            'data'    => $asistencia,
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
