<?php

namespace App\Services\Ficha;

use App\Models\Ficha\Ficha;
use Exception;
use Illuminate\Support\Facades\DB;

class FichaServices
{
    public function getfichas()
    {
        $ficha = Ficha::all();

        if (count($ficha) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay fichas registrados",
                "data" => $ficha
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Fichas obtenidos con éxito",
            "data" => $ficha
        ];
    }
    public function Createfichas(array $data)
    {
        $ficha = Ficha::Create([
            'ficha' => $data['ficha'],
            'program_id' => $data['programa'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'ficha creado con éxito',
            'data' => $ficha,
        ];
    }

    public function updateFicha(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $ficha = Ficha::find($id);

            if (!$ficha) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El ficha no existe",
                ];
            }

            $ficha->update([
                'ficha' => $data['ficha'],
                'program_id' => $data['programa'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "ficha actualizado con éxito",
                "data" => $ficha
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el ficha",
            ];
        }
    }

    public function deleteficha($id)
    {
        $ficha = Ficha::find($id);
    

        if (!$ficha)
            return [
                "error" => true,
                "code" => 404,
                "message" => "El ficha no existe",
            ];

        $ficha->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "ficha eliminado con éxito",
        ];
    }
}
