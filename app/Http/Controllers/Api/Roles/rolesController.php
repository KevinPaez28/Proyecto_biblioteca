<?php

namespace App\Http\Controllers\Api\Roles;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\createRoles;
use App\Http\Requests\Roles\updateRoles;
use App\Http\Requests\RolesPermissions\permissions;
use App\Services\roleServices\RoleServices;
use Illuminate\Http\Request;

class rolesController extends Controller
{
    protected $RoleServices;


    public function __construct(RoleServices $rolesservices)
    {
        $this->RoleServices = $rolesservices;
    }

    public function getAll()
    {
        $response = $this->RoleServices->getRoles();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function permissions()
    {
        $response = $this->RoleServices->getAllpermisos();


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function create(createRoles $request)
    {
        $data = $request->validated();

        $response = $this->RoleServices->createRoles($data);


        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function update(updateRoles $request, string $id)
    {
        $data = $request->validated();

        $response = $this->RoleServices->updateRoles($data, $id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function editRoles(permissions $request, string $id)
    {
        $data = $request->validated();
    
        // Incluimos permisos enviados desde el frontend
        $permisos = $data['permisos'] ?? []; // array de IDs
        $data['permisos'] = $permisos;
    
        $response = $this->RoleServices->editRoles($data, $id);
    
        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);
    
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    
    public function delete(string $id)
    {
        $response = $this->RoleServices->deleteRoles($id);

        if ($response['error'])
            return ResponseFormatter::error($response['message'], $response['code']);

        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
