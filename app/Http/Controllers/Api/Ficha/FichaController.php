<?php

namespace App\Http\Controllers\Api\Ficha;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ficha\createFicha;
use App\Http\Requests\Ficha\updateFicha;
use App\Services\Ficha\FichaServices;
use Illuminate\Http\Request;

class FichaController extends Controller
{
    protected $FichaServices;


    public function __construct(FichaServices $fichaservices)
    {
        $this->FichaServices = $fichaservices;
    }

    public function getAll()
    {
        $response = $this->FichaServices->getfichas();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createFicha $request)
    {
        $data = $request->validated();

        $response = $this->FichaServices->Createfichas($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateFicha $request, string $id)
    {
        $data = $request->validated();

        $response = $this->FichaServices->updateficha($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->FichaServices->deleteficha($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
