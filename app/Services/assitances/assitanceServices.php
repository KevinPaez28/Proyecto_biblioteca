<?php

namespace App\Services\assitances;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Events\EventAssistanceCreated;
use App\Models\assitances\assitances;
use App\Models\Ficha\Ficha;
use App\Models\Ficha_users\ficha_user;
use App\Models\Reasons\reasons;
use App\Models\Schedules\Schedules;
use App\Models\Shifts\Shifts;
use App\Models\User\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class assitanceServices
{
    public function getAssistances(array $filters = [], int $page = 1, int $perPage = 10)
    {
        $query = assitances::with([
            'user.perfil',
            'user.fichas',
            'reason',
            'user.roles'
        ]);

        // 🔍 FILTROS
        if (!empty($filters['nombre'])) {
            $query->whereHas('user.perfil', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['nombre'] . '%');
            });
        }

        if (!empty($filters['apellido'])) {
            $query->whereHas('user.perfil', function ($q) use ($filters) {
                $q->where('last_name', 'like', '%' . $filters['apellido'] . '%');
            });
        }

        if (!empty($filters['documento'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('document', 'like', '%' . $filters['documento'] . '%');
            });
        }

        if (!empty($filters['ficha'])) {
            $query->whereHas('user.fichas', function ($q) use ($filters) {
                $q->where('ficha', 'like', '%' . $filters['ficha'] . '%');
            });
        }

        if (!empty($filters['fecha'])) {
            $query->whereDate('created_at', $filters['fecha']);
        }

        if (!empty($filters['motivo'])) {
            $query->whereHas('reason', function ($q) use ($filters) {
                $q->where('name', $filters['motivo']);
            });
        }

        if (!empty($filters['rol'])) {
            $query->whereHas('user.roles', function ($q) use ($filters) {
                $q->where('name', $filters['rol']);
            });
        }

        // 📄 PAGINACIÓN
        $assistances = $query
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        $records = $assistances->map(function ($a) {
            $user   = $a->user;
            $perfil = $user->perfil ?? null;
            $ficha  = $user->fichas->first();

            $fecha = $a->created_at->setTimezone('America/Bogota');

            return [
                "Ficha"     => $ficha->ficha ?? "",
                "Documento" => $user->document ?? "",
                "FirstName" => $perfil->name ?? "",
                "LastName"  => $perfil->last_name ?? "",
                "Date"      => $fecha->format('d/m/Y'),
                "Time"      => $fecha->format('h:i:s A'), // ✅ 12 horas
                "DateTime"  => $fecha->format('d/m/Y h:i:s A'),
                "Reason"    => $a->reason->name ?? "",
                "Role"      => $user->roles->pluck('name')->implode(', '),
                "id"        => $a->id
            ];
        });

        return [
            "error"   => false,
            "code"    => 200,
            "message" => $records->isEmpty()
                ? "No hay asistencias registradas"
                : "Asistencias obtenidas con éxito",
            "data"    => [
                "records" => $records,
                "meta"    => [
                    "current_page" => $assistances->currentPage(),
                    "last_page"    => $assistances->lastPage(),
                    "per_page"     => $assistances->perPage(),
                    "total"        => $assistances->total(),
                ]
            ]
        ];
    }


    public function getEventAttendances(array $filters = [])
    {
        $query = assitances::with([
            'user.perfil',
            'user.fichas',
            'user.roles',
            'event'
        ])->whereNotNull('event_id');

        // ================= FILTROS =================
        if (!empty($filters['nombre'])) {
            $query->whereHas('user.perfil', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['nombre'] . '%');
            });
        }

        if (!empty($filters['apellido'])) {
            $query->whereHas('user.perfil', function ($q) use ($filters) {
                $q->where('last_name', 'like', '%' . $filters['apellido'] . '%');
            });
        }

        if (!empty($filters['documento'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('document', 'like', '%' . $filters['documento'] . '%');
            });
        }

        if (!empty($filters['ficha'])) {
            $query->whereHas('user.fichas', function ($q) use ($filters) {
                $q->where('ficha', 'like', '%' . $filters['ficha'] . '%');
            });
        }

        if (!empty($filters['fecha'])) {
            $query->whereDate('created_at', $filters['fecha']);
        }

        if (!empty($filters['evento'])) {
            $query->whereHas('event', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['evento'] . '%');
            });
        }

        if (!empty($filters['rol'])) {
            $query->whereHas('user.roles', function ($q) use ($filters) {
                $q->where('name', $filters['rol']);
            });
        }

        // ================= OBTENER DATOS =================
        $data = $query->get()
            ->groupBy(fn($a) => optional($a->user->fichas->first())->id) // Agrupa por ficha
            ->map(function ($group) {
                $a = $group->first(); // Tomamos solo la primera asistencia de cada ficha
                return [
                    'Ficha'     => optional($a->user->fichas->first())->ficha ?? '',
                    'Documento' => $a->user->document ?? '',
                    'FirstName' => $a->user->perfil->name ?? '',
                    'LastName'  => $a->user->perfil->last_name ?? '',
                    'DateTime'  => $a->created_at,
                    'Event'     => $a->event->name ?? '',
                    'Role'      => optional($a->user->roles->first())->name ?? ''
                ];
            })
            ->values(); // Reindexa la colección

        return [
            "error"   => false,
            "success" => true,
            "code"    => 200,
            "message" => $data->isEmpty()
                ? "No hay asistencias a eventos registradas"
                : "Asistencias a eventos obtenidas con éxito",
            "data" => $data
        ];
    }



    public function exportAssistances(array $filters = [])
    {
        try {
            ob_end_clean();

            $query = assitances::with([
                'user.perfil',
                'user.fichas',
                'user.roles',
                'event'
            ])->whereNotNull('event_id');

            if (!empty($filters['fecha_inicio']) && !empty($filters['fecha_fin'])) {
                $query->whereBetween('created_at', [
                    $filters['fecha_inicio'],
                    $filters['fecha_fin'] . ' 23:59:59'
                ]);
            }

            $asistencias = $query->get();

            // 🔑 VALIDACIÓN: Si no hay asistencias
            if ($asistencias->isEmpty()) {
                return [
                    'error' => true,
                    'code' => 404,
                    'message' => 'No se encontraron asistencias en el rango de fechas seleccionado'
                ];
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Encabezados
            $sheet->setCellValue('A1', 'Ficha');
            $sheet->setCellValue('B1', 'Documento');
            $sheet->setCellValue('C1', 'Nombre');
            $sheet->setCellValue('D1', 'Apellido');
            $sheet->setCellValue('E1', 'Fecha');
            $sheet->setCellValue('F1', 'Evento');
            $sheet->setCellValue('G1', 'Rol');
            $sheet->getStyle('A1:G1')->getFont()->setBold(true);

            $fila = 2;
            foreach ($asistencias as $a) {
                $sheet->setCellValue('A' . $fila, optional($a->user->fichas->first())->ficha ?? '');
                $sheet->setCellValue('B' . $fila, $a->user->document ?? '');
                $sheet->setCellValue('C' . $fila, $a->user->perfil->name ?? '');
                $sheet->setCellValue('D' . $fila, $a->user->perfil->last_name ?? '');

                $sheet->setCellValue('E' . $fila, $a->created_at);
                $sheet->getStyle('E' . $fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DATETIME);

                $sheet->setCellValue('F' . $fila, $a->event->name ?? '');
                $sheet->setCellValue('G' . $fila, optional($a->user->roles->first())->name ?? '');
                $fila++;
            }

            $writer = new Xlsx($spreadsheet);
            $fileName = 'Asistencias_' . date('Y-m-d') . '.xlsx';

            $responseExcel = new StreamedResponse(function () use ($writer) {
                $writer->save('php://output');
            });

            $responseExcel->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $responseExcel->headers->set('Content-Disposition', 'attachment; filename*=UTF-8\'\'' . $fileName);
            $responseExcel->headers->set('Content-Transfer-Encoding', 'binary');
            $responseExcel->headers->set('Pragma', 'public');
            $responseExcel->headers->set('Cache-Control', 'max-age=0, no-cache, no-store, must-revalidate');
            $responseExcel->headers->set('Expires', '0');

            return [
                'error' => false,
                'code' => 200,
                'message' => 'Excel generado correctamente',
                'data' => $responseExcel
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'code' => 500,
                'message' => $e->getMessage()
            ];
        }
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

    public function createByEventAndFicha(array $data): array
    {
        // ================= VALIDACIÓN BÁSICA =================
        if (empty($data['event_id']) || empty($data['ficha_id'])) {
            return [
                'error'   => true,
                'code'    => 422,
                'message' => 'Debe enviar event_id y ficha_id'
            ];
        }

        $eventId = $data['event_id'];
        $fichaId = $data['ficha_id'];
        $created = [];
        $skipped = [];

        // ================= VALIDAR MOTIVO "EVENTO" =================
        $motivoEvento = reasons::whereRaw('LOWER(name) = ?', ['evento'])->first();

        if (!$motivoEvento) {
            return [
                'error'   => true,
                'code'    => 400,
                'message' => 'No existe un motivo llamado "Evento" en el sistema'
            ];
        }

        // ================= VALIDAR HORARIO ACTIVO =================
        $horaActual = now()->format('H:i');

        $horario = Schedules::where('start_time', '<=', $horaActual)
            ->where('end_time', '>=', $horaActual)
            ->first();

        if (!$horario) {
            return [
                'error'   => true,
                'code'    => 400,
                'message' => 'No hay horario activo en este momento'
            ];
        }

        // ================= VALIDAR JORNADA =================
        $jornada = $horario->shifts()->first();

        if (!$jornada) {
            return [
                'error'   => true,
                'code'    => 400,
                'message' => 'No se encontró jornada asociada al horario'
            ];
        }

        // ================= USUARIOS DE LA FICHA =================
        $usuariosIds = ficha_user::where('ficha_id', $fichaId)
            ->pluck('usuario_id');

        if ($usuariosIds->isEmpty()) {
            return [
                'error'   => true,
                'code'    => 400,
                'message' => 'La ficha no tiene usuarios asignados'
            ];
        }

        $usuarios = User::whereIn('id', $usuariosIds)->get();

        // ================= REGISTRO DE ASISTENCIAS =================
        foreach ($usuarios as $usuario) {

            $existe = assitances::where('user_id', $usuario->id)
                ->where('working_day_id', $jornada->id)
                ->whereDate('created_at', today())
                ->exists();

            if ($existe) {
                $skipped[] = $usuario->perfil->name;
                continue;
            }

            assitances::create([
                'user_id'        => $usuario->id,
                'working_day_id' => $jornada->id,
                'event_id'       => $eventId,
                'reason_id'      => $motivoEvento->id,
            ]);

            $created[] = $usuario->perfil->name;
        }

        // ================= RESPUESTA FINAL =================
        return [
            'error'   => false,
            'code'    => 201,
            'message' => [
                'created' => $created,
                'skipped' => $skipped
            ]
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

    public function deleteAprendices($id)
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
    public function deleteAllByFicha($data)
    {
        // 1. Buscamos la ficha por su número
        $ficha = Ficha::where('ficha', $data['ficha'])->first();

        if (!$ficha) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "No se encontró la ficha con ese número",
            ];
        }

        // 2. Obtenemos los usuarios de la ficha (muchos a muchos)
        $usuarios = ficha_user::where('ficha_id', $ficha->id)->get();

        if ($usuarios->isEmpty()) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "No se encontraron usuarios en esta ficha",
            ];
        }

        // ⚡ Usar la columna correcta: 'usuario_id'
        $userIds = $usuarios->pluck('usuario_id')->toArray();

        // 3. Buscamos las asistencias de esos usuarios
        $query = assitances::whereIn('user_id', $userIds);

        if (!empty($data['event_id'])) {
            $query->where('event_id', (int) $data['event_id']);
        }

        $asistencias = $query->get();

        if ($asistencias->isEmpty()) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "No se encontraron asistencias de estos usuarios en el evento",
            ];
        }

        // 4. Guardamos los datos eliminados en un objeto
        $asistenciasObj = [];
        foreach ($asistencias as $asistencia) {
            $asistenciasObj[] = (object)[
                'id' => $asistencia->id,
                'user_id' => $asistencia->user_id,
                'event_id' => $asistencia->event_id,
            ];
        }

        // 5. Eliminamos las asistencias
        $query->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Asistencias eliminadas correctamente",
            "data" => $asistenciasObj,
        ];
    }
}
