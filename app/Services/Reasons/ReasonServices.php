<?php

namespace App\Services\Reasons;

use App\Models\Reasons\reasons;
use Exception;
use Illuminate\Support\Facades\DB;

class ReasonServices
{

    public function getReasons()
    {
        $reasons = reasons::with('state:id,name')->get();

        if ($reasons->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay motivos registrados",
                "data" => $reasons
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Motivos obtenidos con éxito",
            "data" => $reasons
        ];
    }

    public function gettoday()
    {
        $reasons = reasons::all();

        if (count($reasons) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay motivos registrados",
                "data" => $reasons
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Motivos obtenidos con éxito",
            "data" => $reasons
        ];
    }
    public function CreateReasons(array $data)
    {
        $reasons = reasons::Create([
            'name' => $data['nombre'],
            'description' => $data['descripcion'],
            'state_reason_id' => $data['estados_id'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Motivo creado con éxito',
            'data' => $reasons,
        ];
    }

    public function updateReasons(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $reasons = reasons::find($id);

            if (!$reasons) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El motivo no existe",
                ];
            }

            $reasons->update([
                'name' => $data['nombre'],
                'description' => $data['descripcion'],
                'state_reason_id' => $data['estados_id'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Motivo actualizado con éxito",
                "data" => $reasons
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el motivo",
            ];
        }
    }

    public function deleteReasons($id)
    {
        $reasons = Reasons::find($id);

        if (!$reasons) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "El motivo no existe",
            ];
        }

        // 👀 Verificar si tiene asistencias asociadas
        if ($reasons->assistances()->exists()) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar el motivo porque tiene asistencias asociadas",
            ];
        }

        $reasons->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Motivo eliminado con éxito",
        ];
    }
}
