<?php

namespace App\Http\Controllers\Api\Ficha;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ficha\createFicha;
use App\Http\Requests\Ficha\updateFicha;
use App\Services\Ficha\FichaServices;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de Fichas (Grupos de formación).
 * * Este controlador administra el ciclo de vida de las fichas, permitiendo
 * agrupar aprendices bajo un código de formación específico.
 */
class FichaController extends Controller
{
    // Propiedad para el servicio de lógica de Fichas
    protected $FichaServices;

    /**
     * Constructor para la inyección del servicio.
     * Mantiene el desacoplamiento delegando las operaciones al Service correspondiente.
     */
    public function __construct(FichaServices $fichaservices)
    {
        $this->FichaServices = $fichaservices;
    }

    /**
     * Obtiene el listado completo de fichas registradas.
     * * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->FichaServices->getfichas();

        // Validación de error proveniente del servicio
        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea una nueva ficha de formación.
     * * @param createFicha $request FormRequest con las reglas de validación de campos.
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(createFicha $request)
    {
        // Se obtienen únicamente los datos que pasaron la validación
        $data = $request->validated();

        $response = $this->FichaServices->Createfichas($data);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualiza la información de una ficha existente.
     * * @param updateFicha $request Reglas de validación para edición.
     * @param string $id Identificador único de la ficha.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(updateFicha $request, string $id)
    {
        $data = $request->validated();

        $response = $this->FichaServices->updateficha($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina una ficha del sistema.
     * * @param string $id ID de la ficha a remover.
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id)
    {
        $response = $this->FichaServices->deleteficha($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}