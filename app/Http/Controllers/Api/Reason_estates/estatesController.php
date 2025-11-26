<?php

namespace App\Http\Controllers\Api\Reason_estates;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reason_estates\createStates;
use App\Http\Requests\Reason_estates\updateStates;
use App\Services\Reason_estates\stateServices;

class estatesController extends Controller
{
    protected $estateServices;


    public function __construct(stateServices $statesServices)
    {
        $this->estateServices = $statesServices;
    }

    public function getAll()
    {
        $response = $this->estateServices->getStates();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createStates $request)
    {
        $data = $request->validated();

        $response = $this->estateServices->CreateStates($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateStates $request, string $id)
    {
        $data = $request->validated();

        $response = $this->estateServices->updateStates($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->estateServices->deleteStates($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
