<?php

namespace App\Http\Controllers\Api\UserStatus;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\state_users\createUserStatus;
use App\Http\Requests\UserstatusServices\updateuser_statuses;
use App\Services\UserstatusServices\UserStatuServices;
use Illuminate\Http\Request;

class UserStatusController extends Controller
{
    protected $userStatusService;

    public function __construct(UserStatuServices $userStatusService)
    {
        $this->userStatusService = $userStatusService;
    }

    public function getAll()
    {
        $response = $this->userStatusService->getAllStatuses();

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function create(createUserStatus $request)
    {
        $data = $request->validated();

        $response = $this->userStatusService->createStatus($data);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function update(updateuser_statuses $request, string $id)
    {
        $data = $request->validated();

        $response = $this->userStatusService->updateStatus($data, $id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function delete(string $id)
    {
        $response = $this->userStatusService->deleteStatus($id);

        if ($response['error']) {
            return ResponseFormatter::error($response['message'], $response['code']);
        }

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
