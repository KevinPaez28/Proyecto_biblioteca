<?php

namespace App\Http\Controllers\Api\states_rooms;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reason_estates\createStates;
use App\Http\Requests\state_rooms\Createstate_rooms;
use App\Http\Requests\state_rooms\updatestate_rooms;

use App\Services\states_rooms\statesroomServices;
use Illuminate\Http\Request;

/**
 * Class StatesRoomsController
 * @package App\Http\Controllers\Api\states_rooms
 *
 * Controlador para la gestión de estados de ambientes (rooms).
 */
class StatesRoomsController extends Controller
{
    /**
     * @var statesroomServices
     * Servicio para la lógica de negocio de los estados de ambientes.
     */
    protected $State_roomServices;

    /**
     * StatesRoomsController constructor.
     * @param statesroomServices $state_roomsServices
     */
    public function __construct(statesroomServices $state_roomsServices)
    {
        $this->State_roomServices = $state_roomsServices;
    }

    /**
     * Obtiene todos los estados de ambientes.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->State_roomServices->getStates();

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea un nuevo estado de ambiente.
     * @param Createstate_rooms $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Createstate_rooms $request)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Crea el estado de ambiente utilizando el servicio.
        $response = $this->State_roomServices->CreateStates($data);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos del nuevo estado de ambiente.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updatestate_rooms $request, string $id)
    {
        $data = $request->validated();

        $response = $this->State_roomServices->updateStates($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->State_roomServices->deleteStates($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
