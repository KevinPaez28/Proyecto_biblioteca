<?php

namespace App\Services\roleServices;

use App\Models\Permissions\permission;
use App\Models\Roles\roles;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleServices
{
    public function getRoles()
    {

        // Traemos todos los roles con sus permisos relacionados
        $roles = Role::with('permissions')->get();

        if ($roles->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay roles registrados",
                "data" => $roles
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Roles obtenidos con éxito",
            "data" => $roles
        ];
    }

    public function getAllPermisos()
    {
        $permisos = Permission::all(['id', 'name']); // solo id y name

        if ($permisos->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay permisos registrados",
                "data" => $permisos
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Permisos obtenidos con éxito",
            "data" => $permisos
        ];
    }




    public function CreateRoles(array $data)
    {
        return [
            'error' => false,
            'code' => 201,
            'message' => 'Rol creado con éxito',
            'data' => Role::create([
                'name' => $data['name'],
            ]),
        ];
    }

    public function updateRoles(array $data, $id)
    {
        try {
            DB::beginTransaction();

            $role = Role::find($id);

            if (!$role) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El rol no existe",
                ];
            }

            $role->update([
                'name' => $data['name'],
            ]);

            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Rol actualizado con éxito",
                "data" => $role
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el rol",
            ];
        }
    }
    public function editRoles(array $data, $id)
    {
        try {
            DB::beginTransaction();

            $role = Role::find($id);

            if (!$role) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El rol no existe",
                ];
            }

            // Actualizamos el nombre
            $role->update([
                'name' => $data['name'],
            ]);

            // Sincronizamos permisos
            if (isset($data['permisos']) && is_array($data['permisos'])) {
                // Esto quita los permisos que ya no están y agrega los nuevos
                $role->syncPermissions($data['permisos']);
            }

            DB::commit();

            // Recargamos permisos para devolverlos al frontend si quieres
            $role->load('permissions');

            return [
                "error" => false,
                "code" => 200,
                "message" => "Rol y permisos actualizados con éxito",
                "data" => $role
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el rol",
            ];
        }
    }

    public function deleteRoles($id)
    {
        $Roles = Role::find($id);

        if (!$Roles)
            return [
                "error" => true,
                "code" => 404,
                "message" => "El rol no existe",
            ];

        $Roles->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Rol eliminado con éxito",
        ];
    }
}
