<?php

namespace App\Http\Controllers\Api\Shifts;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shifts\createShifts;
use App\Http\Requests\Shifts\updateShifts;
use App\Services\Shifts\ShiftServices;
use Illuminate\Http\Request;

class ShiftsController extends Controller
{
    protected $ShiftServices;


    public function __construct(ShiftServices $Shiftservices)
    {
        $this->ShiftServices = $Shiftservices;
    }

    public function getAll()
    {
        $response = $this->ShiftServices->getShifts();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createShifts $request)
    {
        $data = $request->validated();

        $response = $this->ShiftServices->CreateShifts($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateShifts $request, string $id)
    {
        $data = $request->validated();

        $response = $this->ShiftServices->updateShifts($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->ShiftServices->deleteShifts($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
