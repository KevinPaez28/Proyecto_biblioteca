<?php

namespace App\Http\Controllers\Api\Roles;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\createRoles;
use App\Http\Requests\Roles\updateRoles;
use App\Http\Requests\RolesPermissions\permissions;
use App\Services\roleServices\RoleServices;
use Illuminate\Http\Request;

/**
 * Class rolesController
 * @package App\Http\Controllers\Api\Roles
 *
 * Controlador para la gestión de roles.
 */
class rolesController extends Controller
{
    /**
     * @var RoleServices
     * Servicio para la lógica de negocio de los roles.
     */
    protected $RoleServices;

    /**
     * rolesController constructor.
     * @param RoleServices $rolesservices
     */
    public function __construct(RoleServices $rolesservices)
    {
        $this->RoleServices = $rolesservices;
    }

    /**
     * Obtiene todos los roles.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $response = $this->RoleServices->getRoles();

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Obtiene todos los permisos.
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissions()
    {
        $response = $this->RoleServices->getAllpermisos();

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Crea un nuevo rol.
     * @param createRoles $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(createRoles $request)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Crea el rol utilizando el servicio.
        $response = $this->RoleServices->createRoles($data);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos del nuevo rol.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }

    /**
     * Actualiza un rol existente.
     * @param updateRoles $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(updateRoles $request, string $id)
    {
        // Valida los datos de la petición.
        $data = $request->validated();

        // Actualiza el rol utilizando el servicio.
        $response = $this->RoleServices->updateRoles($data, $id);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito con los datos del rol actualizado.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    public function editRoles(permissions $request, string $id)
    {
        $data = $request->validated();
    
        // Incluimos permisos enviados desde el frontend
        $permisos = $data['permisos'] ?? []; // array de IDs.
        $data['permisos'] = $permisos;
    
        // Edita los roles utilizando el servicio.
        $response = $this->RoleServices->editRoles($data, $id);
    
        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);
    
        // Si no hay error, devuelve una respuesta de éxito con los datos del rol editado.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
    
    /**
     * Elimina un rol existente.
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id)
    {
        $response = $this->RoleServices->deleteRoles($id);

        if ($response['error'])
            // Si hay un error, devuelve una respuesta de error.
            return ResponseFormatter::error($response['message'], $response['code']);

        // Si no hay error, devuelve una respuesta de éxito.
        return ResponseFormatter::success($response['message'], $response['code'], $response['data'] ?? []);
    }
}
