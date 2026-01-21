<?php

namespace App\Services\History;

use App\Models\History\History;
use Exception;
use Illuminate\Support\Facades\DB;

class historyServices
{
    public function getHistory()
    {
        $history = History::with([
            'action',
            'user.perfil'
        ])->get();
    
        if ($history->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay historial registrado",
                "data" => $history
            ];
        }
    
        return [
            "error" => false,
            "code" => 200,
            "message" => "Historiales obtenidos con éxito",
            "data" => $history
        ];
    }
    


    public function CreateHistory(array $data)
    {
        $History = History::Create([
            'usuario_id' => $data['usuario_id'],
            'acction_id' => $data['accion'],
            'description' => $data['descripcion'],
            'model_id' => $data['modelo_id'],
            'model_type' => $data['tipo_modelo'],
            'creation_date' => $data['fecha_creacion'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Historial creada con éxito',
            'data' => $History,
        ];
    }

    public function updateHistory(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $History = History::find($id);

            if (!$History) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El historial no existe",
                ];
            }

            $History->update([
                'usuario_id' => $data['usuario_id'],
                'acction_id' => $data['accion'],
                'description' => $data['descripcion'],
                'model_id' => $data['modelo_id'],
                'model_type' => $data['tipo_modelo'],
                'creation_date' => $data['fecha_creacion'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Historial actualizado con éxito",
                "data" => $History
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el historial",
            ];
        }
    }

    public function deleteHistory($id)
    {
        $History = History::find($id);


        if (!$History)
            return [
                "error" => true,
                "code" => 404,
                "message" => "El historial no existe",
            ];

        $History->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Historial eliminado con éxito",
        ];
    }
}
