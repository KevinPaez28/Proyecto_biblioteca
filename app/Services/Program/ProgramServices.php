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
        $program = Program::orderBy('training_program', 'asc')->get();

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
    public function getProgramByInformation(array $filters = [], $perPage = 10)
    {
        try {

            $query = Program::query()->orderBy('id');

            // filtro por nombre del programa
            if (!empty($filters['nombre'])) {
                $query->where('training_program', 'like', '%' . $filters['nombre'] . '%');
            }

            $programs = $query->paginate($perPage);

            $records = $programs->map(function ($program) {
                return [
                    'id' => $program->id,
                    'training_program' => $program->training_program,
                ];
            });

            return [
                "error" => false,
                "code" => 200,
                "message" => $programs->isEmpty()
                    ? "No hay programas registrados"
                    : "Programas obtenidos con éxito",
                "data" => [
                    "records" => $records,
                    "meta" => [
                        "current_page" => $programs->currentPage(),
                        "last_page" => $programs->lastPage(),
                        "per_page" => $programs->perPage(),
                        "total" => $programs->total(),
                    ]
                ]
            ];
        } catch (Exception $e) {

            return [
                "error" => true,
                "code" => 500,
                "message" => "Error al obtener programas",
                "errors" => [$e->getMessage()]
            ];
        }
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

        // Validar que exista
        if (!$program) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "El programa no existe",
            ];
        }

        //  Verificar si tiene fichas relacionadas
        if ($program->fichas()->count() > 0) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar el programa porque tiene fichas asociadas",
            ];
        }

        // Eliminar
        $program->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Programa eliminado con éxito",
        ];
    }
}
