<?php

namespace App\Http\Controllers\Api\Events;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reason_estates\createEvents;
use App\Http\Requests\Reason_estates\updateEvents;
use App\Http\Requests\Rooms\createRooms;
use App\Http\Requests\Rooms\updateRooms;
use App\Services\Events\EventServices;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected $EventServices;


    public function __construct(EventServices $Eventservices)
    {
        $this->EventServices = $Eventservices;
    }

    public function getAll()
    {
        $response = $this->EventServices->getEvents();

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createEvents $request)
    {
        $data = $request->validated();

        $response = $this->EventServices->CreateEvents($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateEvents $request, string $id)
    {
        $data = $request->validated();

        $response = $this->EventServices->updateEvents($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->EventServices->deleteEvents($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
