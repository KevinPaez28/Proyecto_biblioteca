<?php

namespace App\Http\Controllers\Api\State_rooms;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\state_rooms\Createstate_rooms;
use App\Http\Requests\state_rooms\updatestate_rooms;
use App\Services\state_rooms\state_roomServices;
use Illuminate\Http\Request;

class state_roomsController extends Controller
{
    protected $State_roomServices;


    public function __construct(state_roomServices $state_roomsServices)
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
    public function create(Createstate_rooms $request)
    {
        $data = $request->validated();

        $response = $this->State_roomServices->CreateStates($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updatestate_rooms $request, string $id)
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
