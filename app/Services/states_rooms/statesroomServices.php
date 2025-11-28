<?php

namespace App\Services\states_rooms;

use App\Models\StatesRooms\States_rooms;
use Exception;
use Illuminate\Support\Facades\DB;

class statesroomServices
{
    public function getStates()
    {
        $estates = States_rooms::all();

        if (count($estates) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay estados registrado",
                "data" => $estates
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Estados obtenidos con éxito",
            "data" => $estates
        ];
    }
    public function CreateStates(array $data)
    {
        $estates = States_rooms::Create([
            'name' => $data['nombre'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Estados creado con éxito',
            'data' => $estates,
        ];
    }

    public function updateStates(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $estates = States_rooms::find($id);

            if (!$estates) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El estado no existe",
                ];
            }

            $estates->update([
                'name' => $data['nombre'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Estado actualizado con éxito",
                "data" => $estates
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el estado",
            ];
        }
    }

    public function deleteStates($id)
    {
        $estates = States_rooms::find($id);


        if (!$estates)
            return [
                "error" => true,
                "code" => 404,
                "message" => "El estado no existe",
            ];

        $estates->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Estado eliminado con éxito",
        ];
    }
}
