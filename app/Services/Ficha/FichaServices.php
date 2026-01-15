<?php

namespace App\Services\Ficha;

use App\Models\Ficha\Ficha;
use Exception;
use Illuminate\Support\Facades\DB;

class FichaServices
{
    public function getfichas()
    {
        $fichas = Ficha::with('programa')->get();

        if ($fichas->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay fichas registradas",
                "data" => []
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Fichas con programa obtenidas con éxito",
            "data" => $fichas
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
            'message' => 'Ficha creado con éxito',
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
                    "message" => "La ficha no existe",
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
                "message" => "Ficha actualizada con éxito",
                "data" => $ficha
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar la ficha",
            ];
        }
    }

    public function deleteficha($id)
    {
        $ficha = Ficha::find($id);

        if (!$ficha) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "La ficha no existe",
            ];
        }

        // ===== Validar que no haya usuarios asociados =====
        if ($ficha->usuarios()->count() > 0) {
            return [
                "error" => true,
                "code" => 400,
                "message" => "No se puede eliminar la ficha porque tiene usuarios asociados",
            ];
        }

        // ===== Eliminar ficha =====
        $ficha->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Ficha eliminada con éxito",
        ];
    }
}
