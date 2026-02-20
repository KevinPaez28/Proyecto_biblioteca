<?php

namespace App\Http\Controllers\Api\History;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\History\createHistory;
use App\Http\Requests\History\updateHistory;
use App\Services\History\historyServices;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    protected $HistoryServices;


    public function __construct(historyServices $historyServices)
    {
        $this->HistoryServices = $historyServices;
    }

    public function getAll()
    {
        $response = $this->HistoryServices->getHistory();

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function create(createHistory $request)
    {
        $data = $request->validated();

        $response = $this->HistoryServices->CreateHistory($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateHistory $request, string $id)
    {
        $data = $request->validated();

        $response = $this->HistoryServices->updateHistory($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    {
        $response = $this->HistoryServices->deleteHistory($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
