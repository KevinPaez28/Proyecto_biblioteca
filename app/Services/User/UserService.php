<?php

namespace App\Services\User;

use App\Models\Ficha\Ficha;
use Illuminate\Support\Str;
use App\Models\Ficha_users\ficha_user;
use App\Models\User\User;
use App\Models\Perfiles\Perfiles;
use App\Models\Profiles\Profiles;
use App\Models\Program\Program;
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
            'document'  => $data['documento'],
            'email'     => $data['correo'],   
            'password'  => bcrypt($data['contrasena']),
            'status_id' => 1,
        ]);
    
        Profiles::create([
            'usuario_id' => $user->id,
            'name'       => $data['nombres'],
            'last_name'  => $data['apellidos'],
            'phone'      => $data['telefono'],
        ]);
    
        if (!empty($data['ficha_id'])) {
            ficha_user::create([
                'usuario_id' => $user->id,
                'ficha_id'   => $data['ficha_id'],
            ]);
        }
    
        $user->sendEmailVerificationNotification();
    
        return [
            'error'   => false,
            'code'    => 201,
            'message' => 'Usuario creado correctamente. Revisa tu correo para verificar la cuenta.',
            'data'    => $user,
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
