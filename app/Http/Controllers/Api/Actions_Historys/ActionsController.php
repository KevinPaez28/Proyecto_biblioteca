<?php

namespace App\Http\Controllers\Api\Actions_Historys;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Actions\createActions;
use App\Http\Requests\Actions\updateActions;
use App\Services\Actions\ActionServices;
use Illuminate\Http\Request;

class ActionsController extends Controller
{
    protected $ActionServices;


    public function __construct(ActionServices $ActionServices)
    {
        $this->ActionServices = $ActionServices;
    }

    public function getAll()
    {
        $response = $this->ActionServices->getActions();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createActions $request)
    {
        $data = $request->validated();

        $response = $this->ActionServices->CreateActions($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateActions $request, string $id)
    {
        $data = $request->validated();

        $response = $this->ActionServices->updateActions($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->ActionServices->deleteActions($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
