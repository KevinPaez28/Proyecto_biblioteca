<?php

namespace App\Services\roleServices;

use App\Models\Roles\roles;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleServices
{
    public function getRoles()
    {
        $roles = Role::all();

        if (count($roles) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay roles registrados",
                "data" => $roles
            ];

        return [
            "error" => false,
            "code" => 200,
            "message" => "Roles obtenidos con éxito",
            "data" => $roles
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
