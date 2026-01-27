<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Imports\ImportApprenticeRequest;
use App\Http\Requests\User\updateRequest;
use App\Http\Requests\User\UserRequest;
use App\Services\ImportExcel\ImportExcelService;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    protected $importService;

    public function __construct(UserService $userservice, ImportExcelService $importService)
    {

        $this->importService = $importService;
        $this->userService = $userservice;
    }

    public function getAll()
    {
        $response = $this->userService->getUser();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    public function profiles(string $id)
{
    $response = $this->userService->getUserById($id);

    if ($response['error']) {
        return ResponseFormatter::error($response['message'], $response['code']);
    }

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
        'estados',
        'ficha',
        'programa'
    ]);

    $response = $this->userService->getAllApprentices($filters);

    if ($response['error']) {
        return ResponseFormatter::error(
            $response['message'],
            $response['code']
        );
    }

    return ResponseFormatter::success(
        $response['message'],
        $response['code'],
        $response['data'] ?? [],
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


    public function import(ImportApprenticeRequest $request)
    {
        $file = $request->file('file');

        $response = $this->importService->importFile($file);

        if ($response['error']) {
            return ResponseFormatter::error(
                $response['message'],
                $response['code'],
                $response['errors'] ?? []
            );
        }

        return ResponseFormatter::success(
            $response['message'],
            $response['code'],
            $response['data'] ?? []
        );
    }
}
