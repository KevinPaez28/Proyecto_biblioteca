<?php

namespace App\Http\Controllers\Api\History;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\History\createHistory;
use App\Http\Requests\History\updateHistory;
use App\Services\History\historyServices;
use Illuminate\Http\Request;

/**
 * Class HistoryController
 * @package App\Http\Controllers\Api\History
 *
 * Controller for managing history records.
 */
class HistoryController extends Controller
{
    /**
     * @var historyServices
     * Services layer for handling history data logic.
     */
    protected $HistoryServices;

    /**
     * HistoryController constructor.
     * @param historyServices $historyServices
     */
    public function __construct(historyServices $historyServices)
    {
        $this->HistoryServices = $historyServices;
    }

    /**
     * Get all history records.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->HistoryServices->getHistory();

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']); // Returns an error response if something goes wrong.
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        ); // Returns a success response with the data.
    }

    /**
     * Create a new history record.
     * @param createHistory $request
     * @return \Illuminate\Http\JsonResponse
     */
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
    /**
     * Update an existing history record.
     * @param updateHistory $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
        $data = $request->validated();

        $response = $this->HistoryServices->updateHistory($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function delete(string $id)
    /**
     * Delete a history record.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    {
        $response = $this->HistoryServices->deleteHistory($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
