<?php

namespace App\Http\Controllers\Api\state_events;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\state_events\CreateEventEstates;
use App\Http\Requests\state_events\updateEventEstates;
use App\Services\state_events\state_eventsServices;
use Illuminate\Http\Request;

class stateEventsController extends Controller
{
    protected $State_EventsServices;


    public function __construct(state_eventsServices $state_eventsServices)
    {
        $this->State_EventsServices = $state_eventsServices;
    }

    public function getAll()
    {
        $response = $this->State_EventsServices->getStates();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(CreateEventEstates $request)
    {
        $data = $request->validated();

        $response = $this->State_EventsServices->CreateStates($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateEventEstates $request, string $id)
    {
        $data = $request->validated();

        $response = $this->State_EventsServices->updateStates($data, $id);

        if ($response['error'])
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
