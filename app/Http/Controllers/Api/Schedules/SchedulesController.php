<?php

namespace App\Http\Controllers\Api\Schedules;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedules\createSchedules;
use App\Http\Requests\Schedules\updateSchedules;
use App\Services\Schedules\SchedulesServices;
use Illuminate\Http\Request;
/**
 * Class SchedulesController
 * @package App\Http\Controllers\Api\Schedules
 *
 * Controlador para la gestión de horarios (Schedules).
 */
class SchedulesController extends Controller
{
    /**
     * @var SchedulesServices
     * Servicio para la lógica de negocio de los horarios.
     */
    protected $ScheduleServices;

    /**
     * SchedulesController constructor.
     * @param SchedulesServices $Scheduleservices
     */
    public function __construct(SchedulesServices $Scheduleservices)
    {
        $this->ScheduleServices = $Scheduleservices;
    }

    /**
     * Obtiene todos los horarios.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->ScheduleServices->getSchedules();

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Obtiene las jornadas y horarios, permitiendo una búsqueda.
     * @param Request $request Contiene el término de búsqueda opcional.
     * @return \Illuminate\Http\JsonResponse
     */
    public function jornadasandhorarios(Request $request)
    {
        // Obtiene el término de búsqueda de la query string.
        $search = $request->query('search');

        // Obtiene las jornadas y horarios utilizando el servicio.
        $response = $this->ScheduleServices->getJornadasAndHorarios($search);

        if ($response['error']) {
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }
    /**
     * Crea un nuevo horario.
     * @param createSchedules $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(createSchedules $request)
    {
        $data = $request->validated();

        $response = $this->ScheduleServices->CreateSchedules($data);


       if ($response['error']) // Si hay un error, devuelve una respuesta de error.
         return ResponseFormatter::error($response['message'], $response['code']);

    // Si no hay error, devuelve una respuesta de éxito con los datos del nuevo horario.

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateSchedules $request, string $id)
    {
        $data = $request->validated();

        $response = $this->ScheduleServices->updateSchedules($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->ScheduleServices->deleteSchedules($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
