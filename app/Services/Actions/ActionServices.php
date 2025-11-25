<?php

namespace App\Services\Actions;

use App\Models\Actions\Actions;
use Exception;
use Illuminate\Support\Facades\DB;

class ActionServices
{
    public function getActions()
    {
        $Actions = Actions::all();

        if (count($Actions) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay acciones registradas",
                "data" => $Actions
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Acciones obtenidos con éxito",
            "data" => $Actions
        ];
    }
    public function CreateActions(array $data)
    {
        $Actions = Actions::Create([
            'name' => $data['nombre'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Accion creada con éxito',
            'data' => $Actions,
        ];
    }

    public function updateActions(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $Actions = Actions::find($id);

            if (!$Actions) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "La accion no existe",
                ];
            }

            $Actions->update([
                'name' => $data['nombre'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Accion actualizada con éxito",
                "data" => $Actions
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar la accion",
            ];
        }
    }

    public function deleteActions($id)
    {
        $Actions = Actions::find($id);


        if (!$Actions)
            return [
                "error" => true,
                "code" => 404,
                "message" => "La accion no existe",
            ];

        $Actions->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Accion eliminado con éxito",
        ];
    }
}
