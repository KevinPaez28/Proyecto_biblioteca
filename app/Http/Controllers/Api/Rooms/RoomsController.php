<?php

namespace App\Http\Controllers\Api\Rooms;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Rooms\createRooms;
use App\Http\Requests\Rooms\updateRooms;
use App\Services\Rooms\RoomServices;
use Illuminate\Http\Request;

class RoomsController extends Controller
{
    protected $RoomsServices;


    public function __construct(RoomServices $roomsservices)
    {
        $this->RoomsServices = $roomsservices;
    }

    public function getAll()
    {
        $response = $this->RoomsServices->getrooms();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createRooms $request)
    {
        $data = $request->validated();

        $response = $this->RoomsServices->Createrooms($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateRooms $request, string $id)
    {
        $data = $request->validated();

        $response = $this->RoomsServices->updaterooms($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->RoomsServices->deleterooms($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
