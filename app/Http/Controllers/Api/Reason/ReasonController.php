<?php

namespace App\Http\Controllers\Api\Reason;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reasons\createReasons;
use App\Http\Requests\Reasons\updateReasons;
use App\Services\Reasons\ReasonServices;
use Illuminate\Http\Request;

/**
 * Class ReasonController
 * @package App\Http\Controllers\Api\Reason
 *
 * Controlador para la gestión de razones.
 */
class ReasonController extends Controller
{
    /**
     * @var ReasonServices
     * Servicio para la lógica de negocio de las razones.
     */
    protected $ReasonServices;

    /**
     * ReasonController constructor.
     * @param ReasonServices $Reasonservices
     */
    public function __construct(ReasonServices $Reasonservices)
    {
        $this->ReasonServices = $Reasonservices;
    }

    /**
     * Obtiene todas las razones.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->ReasonServices->getReasons();

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea una nueva razón.
     * @param createReasons $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(createReasons $request)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Crea la razón utilizando el servicio.
        $response = $this->ReasonServices->CreateReasons($data);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos de la nueva razón.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualiza una razón existente.
     * @param updateReasons $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(updateReasons $request, string $id)
    {
        $data = $request->validated();

        $response = $this->ReasonServices->updateReasons($data, $id);

       if ($response['error']) return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->ReasonServices->deleteReasons($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}