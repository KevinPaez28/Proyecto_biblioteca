<?php

namespace App\Services\assitances;

use App\Models\assitances\assitances;
use App\Models\Schedules\Schedules;
use App\Models\Shifts\Shifts;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class assitanceServices
{
    public function getAssistances(array $filters = [])
    {
        $query = DB::table('assistances as a')
            ->leftJoin('users as u', 'a.user_id', '=', 'u.id')
            ->leftJoin('profiles as p', 'u.id', '=', 'p.usuario_id')
            ->leftJoin('ficha_user as fu', 'p.usuario_id', '=', 'fu.usuario_id')
            ->leftJoin('ficha as f', 'fu.ficha_id', '=', 'f.id')
            ->leftJoin('reasons as r', 'a.reason_id', '=', 'r.id')
            ->leftJoin('model_has_roles as mr', function ($join) {
                $join->on('u.id', '=', 'mr.model_id')
                    ->where('mr.model_type', 'App\\Models\\User\\User');
            })
            ->leftJoin('roles as ro', 'mr.role_id', '=', 'ro.id')
            ->select(
                DB::raw('COALESCE(f.ficha, "") as Ficha'),
                DB::raw('COALESCE(u.document, "") as Documento'),
                DB::raw('COALESCE(p.name, "") as FirstName'),
                DB::raw('COALESCE(p.last_name, "") as LastName'),
                'a.created_at as DateTime',
                DB::raw('COALESCE(r.name, "") as Reason'),
                DB::raw('COALESCE(ro.name, "") as Role')
            );

        // 🔍 FILTROS
        if (!empty($filters['nombre'])) {
            $query->where('p.name', 'like', '%' . $filters['nombre'] . '%');
        }

        if (!empty($filters['apellido'])) {
            $query->where('p.last_name', 'like', '%' . $filters['apellido'] . '%');
        }

        if (!empty($filters['documento'])) {
            $query->where('u.document', 'like', '%' . $filters['documento'] . '%');
        }

        if (!empty($filters['ficha'])) {
            $query->where('f.ficha', 'like', '%' . $filters['ficha'] . '%');
        }

        if (!empty($filters['fecha'])) {
            $query->whereDate('a.created_at', $filters['fecha']);
        }

        if (!empty($filters['motivo'])) {
            $query->where('r.name', $filters['motivo']);
        }

        if (!empty($filters['rol'])) {
            $query->where('ro.name', $filters['rol']);
        }

        $data = $query->get();

        return [
            "error" => false,
            "code" => 200,
            "message" => $data->isEmpty()
                ? "No hay asistencias registradas"
                : "Asistencias obtenidas con éxito",
            "data" => $data
        ];
    }


    public function getTotalByDay()
    {
        $total = assitances::whereDate('created_at', today())->count();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Total asistencias de usuarios por dia",
            "data" => [
                "total" => $total
            ]
        ];
    }

    public function getTotalByWeek()
    {
        $total = assitances::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Total asistencias de usuarios por semena",
            "data" => [
                "total" => $total
            ]
        ];
    }
    public function getTotalByMonth()
    {
        $total = assitances::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Total asistencias de usuarios por mes",
            "data" => [
                "total" => $total
            ]
        ];
    }
    public function getTotalGraduates()
    {
        $total = assitances::whereHas('user', function ($q) {
            $q->whereHas('roles', function ($r) {
                $r->where('name', 'EGRESADO');
            });
        })->count();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Total de asistencias de usuarios egresados",
            "data" => [
                "total" => $total
            ]
        ];
    }


    public function getAssistancesByMonth()
    {
        \Carbon\Carbon::setLocale('es'); // Asegurarse que los meses estén en español

        $query = assitances::select(
            DB::raw('MONTH(created_at) as mes'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $labels = [];
        $values = [];

        foreach ($query as $row) {
            $labels[] = now()->month($row->mes)->translatedFormat('F');
            $values[] = $row->total;
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Estadísticas de asistencias por mes",
            "data" => [
                "labels" => $labels,
                "values" => $values
            ]
        ];
    }

    public function getAssistancesByEvent()
    {
        $data = DB::table('assistances')
            ->join('events', 'assistances.event_id', '=', 'events.id')
            ->select(
                'events.name',
                DB::raw('COUNT(assistances.id) as total')
            )
            ->groupBy('events.name')
            ->get();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Estadísticas de asistencias por evento",
            "data" => $data
        ];
    }
    public function CreateAssistances(array $data)
    {
        // 1. Validar usuario
        $usuario = User::where('document', $data['usuario_id'])->first();
        if (!$usuario) {
            return [
                'error' => true,
                'code' => 404,
                'message' => 'El usuario con ese documento no existe.',
            ];
        }

        // 2. Hora actual
        $horaActual = now()->format('H:i');

        // 3. Buscar horario activo
        $horario = Schedules::where('start_time', '<=', $horaActual)
            ->where('end_time', '>=', $horaActual)
            ->first();

        if (!$horario) {
            return [
                'error' => true,
                'code' => 422,
                'message' => 'No hay ningún horario activo a esta hora',
            ];
        }

        // 4. Obtener jornada
        $jornada = $horario->shifts()->first();
        if (!$jornada) {
            return [
                'error' => true,
                'code' => 422,
                'message' => 'No hay ninguna jornada activa a esta hora',
            ];
        }

        // Verificar asistencia SOLO en la misma jornada DEL MISMO DÍA
        $existe = assitances::where('user_id', $usuario->id)
            ->where('working_day_id', $jornada->id)
            ->whereDate('created_at', today())
            ->exists();

        if ($existe) {
            return [
                'error' => true,
                'code' => 409,
                'message' => 'El usuario ya tiene una asistencia registrada en esta jornada hoy.',
            ];
        }

        // 6. Crear asistencia
        $asistencia = assitances::create([
            'user_id'        => $usuario->id,
            'working_day_id' => $jornada->id,
            'reason_id'      => $data['motivo'],
            'event_id'       => $data['evento'] ?? null,
        ]);

        return [
            'error'   => false,
            'code'    => 201,
            'message' => 'Asistencia creada con éxito',
            'data'    => $asistencia,
        ];
    }


    public function updateAssistances(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $rooms = assitances::find($id);

            if (!$rooms) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "La asistencia no existe",
                ];
            }

            $rooms->update([
                'name' => $data['nombre'],
                'description' => $data['descripcion'],
                'state_room_id' => $data['estado_id'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Asistencia actualizada con éxito",
                "data" => $rooms
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar la asistencia",
            ];
        }
    }

    public function deleteAssistances($id)
    {
        $rooms = assitances::find($id);


        if (!$rooms)
            return [
                "error" => true,
                "code" => 404,
                "message" => "La asistencia no existe",
            ];

        $rooms->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Asistencia eliminada con éxito",
        ];
    }
}
