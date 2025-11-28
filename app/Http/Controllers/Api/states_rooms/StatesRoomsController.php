<?php

namespace App\Http\Controllers\Api\states_rooms;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rooms\createRooms;
use App\Http\Requests\Rooms\updateRooms;
use App\Services\states_rooms\statesroomServices;
use Illuminate\Http\Request;

class StatesRoomsController extends Controller
{
    protected $State_roomServices;


    public function __construct(statesroomServices $state_roomsServices)
    {
        $this->State_roomServices = $state_roomsServices;
    }

    public function getAll()
    {
        $response = $this->State_roomServices->getStates();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createRooms $request)
    {
        $data = $request->validated();

        $response = $this->State_roomServices->CreateStates($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateRooms $request, string $id)
    {
        $data = $request->validated();

        $response = $this->State_roomServices->updateStates($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->State_roomServices->deleteStates($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
