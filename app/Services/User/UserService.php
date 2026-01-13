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
use Spatie\Permission\Models\Role;

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

        $rolId = $data['rol'];
        $rolModel = Role::find($rolId);

        if (!$rolModel) {
            return [
                'error'   => true,
                'code'    => 404,
                'message' => "Rol no encontrado con ID: $rolId",
            ];
        }

        $user->assignRole($rolModel->name); // ahora sí usamos el nombre del rol
        // ===========================

        if (!empty($data['ficha_id'])) {
            ficha_user::create([
                'usuario_id' => $user->id,
                'ficha_id'   => $data['ficha_id'],
            ]);
        }

        // Solo admin recibe correo
        if ($rolModel->name === 'Administrador') {
            $user->sendEmailVerificationNotification();

            return [
                'error'   => false,
                'code'    => 201,
                'message' => 'Usuario creado correctamente. Revisa tu correo para verificar la cuenta.',
                'data'    => $user,
            ];
        }

        return [
            'error'   => false,
            'code'    => 201,
            'message' => 'Usuario creado correctamente.',
            'data'    => $user,
        ];
    }


    public function getAllInformation(array $filters = [])
    {
        $query = User::with(['perfil', 'roles', 'status'])
            ->orderBy('id');

        // FILTROS
        if (!empty($filters['nombre'])) {
            $query->whereHas('perfil', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['nombre'] . '%');
            });
        }

        if (!empty($filters['apellido'])) {
            $query->whereHas('perfil', function ($q) use ($filters) {
                $q->where('last_name', 'like', '%' . $filters['apellido'] . '%');
            });
        }

        if (!empty($filters['documento'])) {
            $query->where('document', 'like', '%' . $filters['documento'] . '%');
        }

        if (!empty($filters['rol'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['rol']);
            });
        }

        if (!empty($filters['estado'])) {
            $query->whereHas('status', function ($q) use ($filters) {
                $q->where('name', $filters['estado']);
            });
        }


        $users = $query->get();

        return [
            "error" => false,
            "code" => 200,
            "message" => $users->isEmpty()
                ? "No hay usuarios registrados"
                : "Usuarios obtenidos con éxito",
            "data" => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'document' => $user->document,
                    'first_name' => $user->perfil->name ?? '',
                    'last_name' => $user->perfil->last_name ?? '',
                    'email' => $user->email,
                    'phone_number' => $user->perfil->phone ?? '',
                    'rol' => $user->roles->pluck('name')->implode(', '),
                    'estado' => $user->status->status ?? '',
                ];
            })
        ];
    }
    public function getAllApprentices(array $filters = [])
    {
        $query = User::with(['perfil', 'roles', 'status', 'fichas.programa'])
            ->whereHas('roles', function ($q) {
                $q->where('name', 'Aprendiz');
            })
            ->orderBy('id');
    
        // ================= FILTROS =================
    
        if (!empty($filters['nombre'])) {
            $query->whereHas('perfil', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['nombre'] . '%');
            });
        }
    
        if (!empty($filters['apellido'])) {
            $query->whereHas('perfil', function ($q) use ($filters) {
                $q->where('last_name', 'like', '%' . $filters['apellido'] . '%');
            });
        }
    
        if (!empty($filters['documento'])) {
            $query->where('document', 'like', '%' . $filters['documento'] . '%');
        }
    
        if (!empty($filters['estado'])) {
            $query->whereHas('status', function ($q) use ($filters) {
                $q->where('name', $filters['estado']);
            });
        }
    
        // 🔥 FILTRO POR NÚMERO DE FICHA
        if (!empty($filters['ficha'])) {
            $query->whereHas('fichas', function ($q) use ($filters) {
                $q->where('ficha', 'like', '%' . $filters['ficha'] . '%');
            });
        }
    
        // 🔥 FILTRO POR PROGRAMA
        if (!empty($filters['programa'])) {
            $query->whereHas('fichas.programa', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['programa'] . '%');
            });
        }
    
        $apprentices = $query->get();
    
        return [
            "error" => false,
            "code" => 200,
            "message" => $apprentices->isEmpty()
                ? "No hay aprendices registrados"
                : "Aprendices obtenidos con éxito",
            "data" => $apprentices->map(function ($user) {
    
                $ficha = $user->fichas->first();
    
                return [
                    'id' => $user->id,
                    'document' => $user->document,
                    'first_name' => $user->perfil->name ?? '',
                    'last_name' => $user->perfil->last_name ?? '',
                    'email' => $user->email,
                    'phone_number' => $user->perfil->phone ?? '',
                    'ficha' => $ficha->ficha ?? '',
                    'programa' => $ficha->programa->training_program ?? '',
                    'rol' => 'Aprendiz',
                    'estado' => $user->status->status ?? '',
                ];
            })
        ];
    }
    


    public function updateUser(array $data, $id)
    {
        try {
            DB::beginTransaction();

            $user = User::with(['perfil', 'roles'])->find($id);

            if (!$user) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El usuario no existe",
                ];
            }

            // ================= USER =================
            $user->update([
                'document'  => $data['documento'],
                'email'     => $data['correo'],
                'status_id' => $data['status_id'],
            ]);

            // ================= PERFIL =================
            if ($user->perfil) {
                $user->perfil->update([
                    'name'      => $data['nombres'],
                    'last_name' => $data['apellidos'],
                    'phone'     => $data['telefono'],
                ]);
            }

            // ================= ROL =================
            if (!empty($data['rol_id'])) {
                $rol = Role::find($data['rol_id']);

                if ($rol) {
                    // elimina roles actuales y asigna el nuevo
                    $user->syncRoles([$rol->name]);
                }
            }

            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Usuario actualizado con éxito",
                "data" => $user
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                "error" => true,
                "code" => 500,
                "message" => "Ocurrió un error al actualizar el usuario",
            ];
        }
    }


    public function deleteUser($id)
    {
        $user = User::withCount('assistances')->find($id);

        if (!$user) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "El usuario no existe",
            ];
        }

        if ($user->assistances_count > 0) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar el usuario porque tiene asistencias registradas",
            ];
        }

        $user->delete();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Usuario eliminado con éxito",
        ];
    }
}
