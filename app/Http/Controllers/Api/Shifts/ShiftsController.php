<?php

namespace App\Http\Controllers\Api\Shifts;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shifts\createShifts;
use App\Http\Requests\Shifts\updateShifts;
use App\Services\Shifts\ShiftServices;
use Illuminate\Http\Request;
/**
 * Class ShiftsController
 * @package App\Http\Controllers\Api\Shifts
 *
 * Controlador para la gestión de turnos (Shifts).
 */
class ShiftsController extends Controller
{
    /**
     * @var ShiftServices
     * Servicio para la lógica de negocio de los turnos.
     */
    protected $ShiftServices;

    /**
     * ShiftsController constructor.
     * @param ShiftServices $Shiftservices
     */
    public function __construct(ShiftServices $Shiftservices)
    {
        $this->ShiftServices = $Shiftservices;
    }

    /**
     * Obtiene todos los turnos.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->ShiftServices->getShifts();

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Obtiene las jornadas y horarios.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getjornadas()
    {
        $response = $this->ShiftServices->getJornadasAndHorarios();

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea un nuevo turno.
     * @param createShifts $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(createShifts $request)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Crea el turno utilizando el servicio.
        $response = $this->ShiftServices->CreateShifts($data);
        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    /**
     * Actualiza un turno existente.
     * @param updateShifts $request
     * @param string $id
     */
    public function update(updateShifts $request, string $id)
    {
        $data = $request->validated();

        $response = $this->ShiftServices->updateShifts($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    /**
     * Elimina un turno existente.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id)
    {
        $response = $this->ShiftServices->deleteShifts($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
