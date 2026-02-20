<?php

namespace App\Http\Controllers\Api\Reason_estates;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reason_estates\createStates;
use App\Http\Requests\Reason_estates\updateStates;
use App\Services\Reason_estates\stateServices;

/**
 * Class estatesController
 * @package App\Http\Controllers\Api\Reason_estates
 *
 * Controlador para la gestión de estados de razones.
 */
class estatesController extends Controller
{
    /**
     * @var stateServices
     * Servicio para la lógica de negocio de los estados.
     */
    protected $estateServices;

    /**
     * estatesController constructor.
     * @param stateServices $statesServices
     */
    public function __construct(stateServices $statesServices)
    {
        $this->estateServices = $statesServices;
    }

    /**
     * Obtiene todos los estados.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->estateServices->getStates();

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea un nuevo estado.
     * @param createStates $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(createStates $request)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Crea el estado utilizando el servicio.
        $response = $this->estateServices->CreateStates($data);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos del nuevo estado.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualiza un estado existente.
     * @param updateStates $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(updateStates $request, string $id)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Actualiza el estado utilizando el servicio.
        $response = $this->estateServices->updateStates($data, $id);

       if ($response['error']) return ResponseFormatter::error($response['message'], $response['code']);
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function delete(string $id)
    {
        $response = $this->estateServices->deleteStates($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
