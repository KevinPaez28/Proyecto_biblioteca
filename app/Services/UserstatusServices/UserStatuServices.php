<?php

namespace App\Services\UserstatusServices;

use App\Models\UserstatusServices\user_statuses;
use Exception;
use Illuminate\Support\Facades\DB;

class UserStatuServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
    }
         // Obtener todos los estados de usuario
    public function getAllStatuses()
    {
        $statuses = user_statuses::all();

        if (count($statuses) === 0) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay estados de usuario registrados",
                "data" => $statuses
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Estados de usuario obtenidos con éxito",
            "data" => $statuses
        ];
    }

    // Crear un nuevo estado de usuario
    public function createStatus(array $data)
    {
        $status = user_statuses::create([
            'status' => $data['status'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Estado de usuario creado con éxito',
            'data' => $status,
        ];
    }

    // Actualizar un estado de usuario existente
    public function updateStatus(array $data, $id)
    {
        try {
            DB::beginTransaction();

            $status = user_statuses::find($id);

            if (!$status) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El estado de usuario no existe",
                ];
            }

            $status->update([
                'status' => $data['status'],
            ]);

            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Estado de usuario actualizado con éxito",
                "data" => $status
            ];
        } catch (Exception $e) {
            DB::rollback();

            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el estado de usuario",
            ];
        }
    }

    // Eliminar un estado de usuario
    public function deleteStatus($id)
    {
        $status = user_statuses::find($id);

        if (!$status) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "El estado de usuario no existe",
            ];
        }

        $status->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Estado de usuario eliminado con éxito",
        ];
    }
    
}
