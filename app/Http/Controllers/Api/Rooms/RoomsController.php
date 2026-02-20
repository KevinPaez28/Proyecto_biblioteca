<?php

namespace App\Http\Controllers\Api\Rooms;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rooms\createRooms;
use App\Http\Requests\Rooms\updateRooms;
use App\Services\Rooms\RoomServices;
use Illuminate\Http\Request;

/**
 * Class RoomsController
 * @package App\Http\Controllers\Api\Rooms
 *
 * Controlador para la gestión de ambientes (Rooms).
 */
class RoomsController extends Controller
{
    /**
     * @var RoomServices
     * Servicio para la lógica de negocio de los ambientes.
     */
    protected $RoomsServices;

    /**
     * RoomsController constructor.
     * @param RoomServices $roomsservices
     */
    public function __construct(RoomServices $roomsservices)
    {
        $this->RoomsServices = $roomsservices;
    }

    /**
     * Obtiene todos los ambientes, permitiendo filtros específicos.
     * @param Request $request Contiene filtros opcionales como nombre, descripción y estado.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        // Se extraen solo los campos permitidos para filtrar la consulta
        $filters = $request->only([
            'nombre',
            'descripcion',
            'estado'
        ]);

        // Se obtienen los ambientes utilizando el servicio.
        $response = $this->RoomsServices->getRooms($filters);

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
     * Crea un nuevo ambiente.
     * @param createRooms $request FormRequest que valida la integridad de los datos del ambiente.
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(createRooms $request)
    {
        // Se obtienen los datos ya validados
        $data = $request->validated();

        $response = $this->RoomsServices->Createrooms($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos del nuevo ambiente.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    /**
     * Actualiza la información de un ambiente existente.
     * @param updateRooms $request FormRequest con las reglas de validación para edición.
     * @param string $id Identificador del ambiente a modificar.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(updateRooms $request, string $id)
    {
        $data = $request->validated();

        $response = $this->RoomsServices->updaterooms($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    /**
     * Elimina un ambiente del sistema.
     * @param string $id ID del ambiente a remover.
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id)
    {
        $response = $this->RoomsServices->deleterooms($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
