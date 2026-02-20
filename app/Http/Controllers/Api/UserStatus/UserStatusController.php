<?php

namespace App\Http\Controllers\Api\UserStatus;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\state_users\createUserStatus;
use App\Http\Requests\UserstatusServices\updateuser_statuses;
use App\Services\UserstatusServices\UserStatuServices;
use Illuminate\Http\Request;

/**
 * Class UserStatusController
 * @package App\Http\Controllers\Api\UserStatus
 *
 * Controlador para la gestión de estados de usuario.
 */
class UserStatusController extends Controller
{
    /**
     * @var UserStatuServices
     * Servicio para la lógica de negocio de los estados de usuario.
     */
    protected $userStatusService;

    /**
     * UserStatusController constructor.
     * @param UserStatuServices $userStatusService
     */
    public function __construct(UserStatuServices $userStatusService)
    {
        $this->userStatusService = $userStatusService;
    }

    /**
     * Obtiene todos los estados de usuario.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        // Obtiene todos los estados de usuario utilizando el servicio.
        $response = $this->userStatusService->getAllStatuses();

        if ($response['error']) {
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea un nuevo estado de usuario.
     * @param createUserStatus $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(createUserStatus $request)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Crea el estado de usuario utilizando el servicio.
        $response = $this->userStatusService->createStatus($data);

        if ($response['error']) {
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        // Si no hay error, devuelve una respuesta de éxito con los datos del nuevo estado de usuario.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualiza un estado de usuario existente.
     * @param updateuser_statuses $request
     * @param string $id
     */
    public function update(updateuser_statuses $request, string $id)
    {
        $data = $request->validated();

        $response = $this->userStatusService->updateStatus($data, $id);

        if ($response['error']) {
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina un estado de usuario existente.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id)
    {
        $response = $this->userStatusService->deleteStatus($id);

        if ($response['error']) {
            
            // Si hay un error, devuelve una respuesta de error.

            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
