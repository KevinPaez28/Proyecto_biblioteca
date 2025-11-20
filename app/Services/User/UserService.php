<?php

namespace App\Services\User;

use App\Models\User\User;
use App\Models\Perfiles\Perfiles;
use Exception;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getUser()
    {
        $users = User::all();

        if (count($users) == 0)
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay Usuarios registrados",
                "data" => $users
            ];


        return [
            "error" => false,
            "code" => 200,
            "message" => "Usuarios obtenidos con éxito",
            "data" => $users
        ];
    }
    public function CreateUser(array $data)
    {

        $user = User::create([
            'document' => $data['documento'],
            'password' => $data['contrasena'],
            'status_id' => $data['estados_id'],
        ]);

        return [
            'error' => false,
            'code' => 201,
            'message' => 'Usuario creada con éxito',
            'data' => $user,
        ];
    }
    public function getAllInformation()
    {
        $users = User::with('profile')->orderBy('id')->get();

        if ($users->isEmpty()) {
            return [
                "error" => false,
                "code" => 200,
                "message" => "No hay usuarios registrados",
                "data" => []
            ];
        }

        $data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'document' => $user->document,
                'first_name' => optional($user->profile)->first_name,
                'last_name' => optional($user->profile)->last_name,
                'email' => optional($user->profile)->email,
                'phone_number' => optional($user->profile)->phone_number,
            ];
        });

        return [
            "error" => false,
            "code" => 200,
            "message" => "Información obtenida con éxito",
            "data" => $data
        ];
    }
    public function updateUser(array $data, $id)
    {
        try {
            DB::beginTransaction();

            // Buscar el programa
            $user = User::find($id);

            if (!$user) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El usuario no existe",
                ];
            }

            $user->update([
                'document' => $data['documento'],
                'status_id' => $data['estado_id'],
            ]);
            //hace commit
            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Usuario actualizado con éxito",
                "data" => $user
            ];
        } catch (Exception $e) {
            //si genero error devuelve a la ultimo cambio de base de datos
            DB::rollback();
            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el usuario",
            ];
        }
    }

    public function deleteCity($id)
    {
        $Users = User::find($id);

        if (!$Users)
            return [
                "error" => true,
                "code" => 404,
                "message" => "El usuario no existe",
            ];

        $Users->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Usuario eliminada con éxito",
        ];
    }
}
