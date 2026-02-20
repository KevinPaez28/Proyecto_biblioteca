<?php

namespace App\Http\Controllers\Api\state_events;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\state_events\CreateEventEstates;
use App\Http\Requests\state_events\updateEventEstates;
use App\Services\state_events\state_eventsServices;

use Illuminate\Http\Request;
/**
 * Class stateEventsController
 * @package App\Http\Controllers\Api\state_events
 *
 * Controlador para la gestión de estados de eventos.
 */
class stateEventsController extends Controller
{
    /**
     * @var state_eventsServices
     * Servicio para la lógica de negocio de los estados de eventos.
     */
    protected $State_EventsServices;

    /**
     * stateEventsController constructor.
     * @param state_eventsServices $state_eventsServices
     */
    public function __construct(state_eventsServices $state_eventsServices)
    {
        $this->State_EventsServices = $state_eventsServices;
    }

    /**
     * Obtiene todos los estados de eventos.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->State_EventsServices->getStates();

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea un nuevo estado de evento.
     * @param CreateEventEstates $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateEventEstates $request)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Crea el estado de evento utilizando el servicio.
        $response = $this->State_EventsServices->CreateStates($data);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos del nuevo estado de evento.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function update(updateEventEstates $request, string $id)
    {
        $data = $request->validated();

        $response = $this->State_EventsServices->updateStates($data, $id);

       if ($response['error']) // Si hay un error, devuelve una respuesta de error.
         return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->State_EventsServices->deleteStates($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
