<?php

namespace App\Services\Profile;

use App\Models\Profiles\Profiles;
use Exception;
use Illuminate\Support\Facades\DB;

class ProfileServices
{
    public function getProfiles()
    {
        $Profile = Profiles::all();

        if (count($Profile) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay perfiles registrados",
                "data" => $Profile
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Perfil obtenido con éxito",
            "data" => $Profile
        ];
    }
    public function createprofiles(array $data)
    {
        $Profile = Profiles::Create([
            'usuario_id' => $data['usuario'],
            'name' => $data['nombre'],
            'last_name' => $data['apellido'],
            'phone' => $data['telefono'],
            'email' => $data['correo'],
            'ficha_id' => $data['programa'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Perfil creado con éxito',
            'data' => $Profile,
        ];
    }

    public function updateProfiles(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $Profile = Profiles::find($id);

            if (!$Profile) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El perfil no existe",
                ];
            }

            $Profile->update([
                'usuario_id' => $data['usuario'],
                'name' => $data['nombre'],
                'last_name' => $data['apellido'],
                'phone' => $data['celular'],
                'email' => $data['correo'],
                'ficha_id' => $data['programa'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "perfil actualizado con éxito",
                "data" => $Profile
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el perfil",
            ];
        }
    }

    public function deleteprofile($id)
    {
        $Profile = Profiles::find($id);


        if (!$Profile)
            return [
                "error" => true,
                "code" => 404,
                "message" => "El perfil no existe",
            ];

        $Profile->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Perfil eliminado con éxito",
        ];
    }
}
