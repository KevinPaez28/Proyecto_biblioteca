<?php

namespace App\Services\Program;

use App\Http\Requests\Programa\updateProgram;
use App\Models\Ficha\Ficha;
use App\Models\Program\Program;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ProgramServices
{
    public function getProgramas()
    {
        $program = Program::all();

        if (count($program) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay programas registrados",
                "data" => $program
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Programas obtenidos con éxito",
            "data" => $program
        ];
    }
    public function CreatePrograma(array $data)
    {
        $programa = Program::Create([
            'training_program' => $data['programa_formacion'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Programa creado con éxito',
            'data' => $programa,
        ];
    }

    public function updateProgram(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $program = Program::find($id);

            if (!$program) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El programa no existe",
                ];
            }

            $program->update([
                'training_program' => $data['programa_formacion'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Programa actualizado con éxito",
                "data" => $program
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el programa",
            ];
        }
    }

    public function deleteProgram($id)
    {
        $program = Program::find($id);
        $fichas = $program->fichas;

        $fichas->count();
        if (!$fichas) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "El programa tiene fichas relacionadas",
            ];
        }

        if (!$program)
            return [
                "error" => true,
                "code" => 404,
                "message" => "El programa no existe",
            ];

        $program->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Programa eliminado con éxito",
        ];
    }
}
