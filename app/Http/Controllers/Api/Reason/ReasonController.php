<?php

namespace App\Http\Controllers\Api\Reason;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reasons\createReasons;
use App\Http\Requests\Reasons\updateReasons;
use App\Services\Reasons\ReasonServices;
use Illuminate\Http\Request;

class ReasonController extends Controller
{
    protected $ReasonServices;

    
    public function __construct(ReasonServices $Reasonservices)
    {
        $this->ReasonServices = $Reasonservices;
    }

    public function getAll()
    {
        $response = $this->ReasonServices->getReasons();

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createReasons $request)
    {
        $data = $request->validated();

        $response = $this->ReasonServices->CreateReasons($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateReasons $request, string $id)
    {
        $data = $request->validated();

        $response = $this->ReasonServices->updateReasons($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->ReasonServices->deleteReasons($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}