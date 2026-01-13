<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\updateRequest;
use App\Http\Requests\User\UserRequest;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userservice)
    {

        $this->userService = $userservice;
    }

    public function getAll()
    {
        $response = $this->userService->getUser();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function getByinformation(Request $request)
    {
        $filters = $request->only([
            'nombre',
            'apellido',
            'documento',
            'rol',
            'estado'
        ]);

        $response = $this->userService->getAllInformation($filters);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }
    public function apprentice(Request $request)
    {
        $filters = $request->only([
            'nombre',
            'apellido',
            'documento',
            'rol',
            'estado'
        ]);

        $response = $this->userService->getAllInformation($filters);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code']
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }

    public function create(UserRequest $request)
    {

        $data = $request->validated();

        $response = $this->userService->CreateUser($data);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateRequest $request, string $id)
    {
        $data = $request->validated();

        $response = $this->userService->updateUser($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function delete(string $id)
    {
        $response = $this->userService->deleteUser($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
