<?php

namespace App\Http\Controllers\Api\Actions_Historys;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Actions\createActions;
use App\Http\Requests\Actions\updateActions;
use App\Services\Actions\ActionServices;
use Illuminate\Http\Request;

/**
 * Controlador para gestionar las acciones del historial.
 *
 * Este controlador proporciona métodos para obtener, crear, actualizar y eliminar acciones del historial.
 */
class ActionsController extends Controller
{
    /**
     * @var ActionServices Servicio para la gestión de acciones.
     */
    protected $ActionServices;

    /**
     * Constructor de la clase.
     *
     * @param ActionServices $ActionServices Servicio para la gestión de acciones.
     */
    public function __construct(ActionServices $ActionServices)
    {
        $this->ActionServices = $ActionServices;
    }

    /**
     * Obtiene todas las acciones del historial.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        // Llama al servicio para obtener todas las acciones
        $response = $this->ActionServices->getActions();

        // Verifica si hubo un error en la respuesta
        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        // Retorna una respuesta exitosa con los datos de las acciones
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea una nueva acción en el historial.
     *
     * @param createActions $request Datos validados para la creación de la acción.
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(createActions $request)
    {
        // Valida los datos de la request
        $data = $request->validated();

        // Llama al servicio para crear la acción
        $response = $this->ActionServices->CreateActions($data);

        // Verifica si hubo un error en la respuesta
        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        // Retorna una respuesta exitosa con los datos de la acción creada
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualiza una acción existente en el historial.
     *
     * @param updateActions $request Datos validados para la actualización de la acción.
     * @param string $id Identificador de la acción a actualizar.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(updateActions $request, string $id)
    {
        // Valida los datos de la request
        $data = $request->validated();

        // Llama al servicio para actualizar la acción
        $response = $this->ActionServices->updateActions($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina una acción del historial.
     *
     * @param string $id Identificador de la acción a eliminar.
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id)
    {
        // Llama al servicio para eliminar la acción
        $response = $this->ActionServices->deleteActions($id);
        // Verifica si hubo un error en la respuesta
        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
