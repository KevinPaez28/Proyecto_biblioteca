<?php

namespace App\Http\Controllers\Api\Events;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Events\createEvent;
use App\Http\Requests\Events\updateEvent;
use App\Services\Events\EventServices;
use Illuminate\Http\Request;

/**
 * Controlador para la gestión de Eventos.
 * * Este controlador centraliza las operaciones de agenda, permitiendo la creación,
 * consulta filtrada, actualización y eliminación de eventos en el sistema.
 */
class EventController extends Controller
{
    // Propiedad para el servicio de lógica de eventos
    protected $EventServices;

    /**
     * Constructor para la inyección del servicio de eventos.
     * Mantiene el desacoplamiento delegando la persistencia y reglas de negocio al Service.
     */
    public function __construct(EventServices $Eventservices)
    {
        $this->EventServices = $Eventservices;
    }

    /**
     * Obtiene todos los eventos permitiendo filtros específicos.
     * * @param Request $request Contiene filtros opcionales como nombre, encargado, estado, fecha y sala.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        // Se extraen solo los campos permitidos para filtrar la consulta
        $filters = $request->only([
            'nombre',
            'encargado',
            'estado',
            'fecha',
            'sala'
        ]);

        $response = $this->EventServices->getEvents($filters);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    /**
     * Obtiene los eventos programados para la fecha actual.
     * * @return \Illuminate\Http\JsonResponse
     */
    public function gettoday()
    {
        $response = $this->EventServices->gettoday();

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Registra un nuevo evento en el sistema.
     * * @param createEvent $request FormRequest que valida la integridad de los datos del evento.
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(createEvent $request)
    {
        // Se obtienen los datos ya validados
        $data = $request->validated();

        $response = $this->EventServices->CreateEvents($data);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualiza la información de un evento existente.
     * * @param updateEvent $request FormRequest con las reglas de validación para edición.
     * @param string $id Identificador del evento a modificar.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(updateEvent $request, string $id)
    {
        $data = $request->validated();

        $response = $this->EventServices->updateEvents($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Elimina un evento del sistema.
     * * @param string $id ID del evento a remover.
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id)
    {
        $response = $this->EventServices->deleteEvents($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
