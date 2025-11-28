<?php

namespace App\Services\state_events;

use App\Models\State_events\state_events;
use Exception;
use Illuminate\Support\Facades\DB;

class state_eventsServices
{
    public function getStates()
    {
        $estates = state_events::all();

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
        $estates = state_events::Create([
            'name' => $data['nombre'],
            'name' => $data['nombre'],
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
            $estates = state_events::find($id);

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
        $estates = state_events::find($id);


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
