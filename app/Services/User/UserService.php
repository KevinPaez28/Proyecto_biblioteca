<?php

namespace App\Services\User;

use App\Models\Ficha\Ficha;
use Illuminate\Support\Str;
use App\Models\Ficha_users\ficha_user;
use App\Models\User\User;
use App\Models\Perfiles\Perfiles;
use App\Models\Profiles\Profiles;
use App\Models\Program\Program;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
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
        DB::beginTransaction(); // Inicia transacción

        try {
            // Crea el usuario
            $user = User::create([
                'document'  => $data['documento'],
                'email'     => $data['correo'],
                'password'  => bcrypt($data['contrasena']),
                'document_type_id' => $data['tipo_documento'],
                'status_id' => 1,

            ]);

            // Crea el perfil
            Profiles::create([
                'usuario_id' => $user->id,
                'name'       => $data['nombres'],
                'last_name'  => $data['apellidos'],
                'phone'      => $data['telefono'],
            ]);

            // Asigna rol
            $rolId = $data['rol'];
            $rolModel = Role::find($rolId);
            if (!$rolModel) {
                DB::rollBack();
                return [
                    'error'   => true,
                    'code'    => 404,
                    'message' => "Rol no encontrado con ID: $rolId",
                ];
            }

            $user->assignRole($rolModel->name);

            // Relación con ficha
            if (!empty($data['ficha_id'])) {
                ficha_user::create([
                    'usuario_id' => $user->id,
                    'ficha_id'   => $data['ficha_id'],
                ]);
            }

            // Solo para Admin o Apoyo: enviar correo
            if ($rolModel->name === 'Administrador' || $rolModel->name === 'Apoyo') {
                try {
                    $user->sendEmailVerificationNotification();
                } catch (Exception $e) {
                    DB::rollBack(); // Revertir todo
                    return [
                        'error'   => true,
                        'code'    => 500,
                        'message' => 'No se pudo enviar el correo de verificación: ' . $e->getMessage(),
                    ];
                }

                DB::commit();
                return [
                    'error'   => false,
                    'code'    => 201,
                    'message' => 'Usuario creado correctamente. Revisa tu correo para verificar la cuenta.',
                    'data'    => $user,
                ];
            }

            DB::commit(); // Todo OK para otros roles
            return [
                'error'   => false,
                'code'    => 201,
                'message' => 'Usuario creado correctamente.',
                'data'    => $user,
            ];
        } catch (Exception $e) {
            DB::rollBack(); // Revertir todo en caso de cualquier error
            return [
                'error'   => true,
                'code'    => 500,
                'message' => 'Error al crear el usuario: ' . $e->getMessage(),
            ];
        }
    }

    public function getUserById($id)
    {
        $user = User::with(['perfil', 'roles', 'status'])->find($id);

        if (!$user) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Usuario no encontrado",
            ];
        }

        return [
            "error" => false,
            "code" => 200,
            "message" => "Usuario obtenido con éxito",
            "data" => [
                'id' => $user->id,
                'document' => $user->document,
                'first_name' => $user->perfil->name ?? '',
                'last_name' => $user->perfil->last_name ?? '',
                'email' => $user->email,
                'phone_number' => $user->perfil->phone ?? '',
                'rol' => $user->roles->pluck('name')->implode(', '),
                'estado' => $user->status->status ?? '',
            ]
        ];
    }

    // =================== MODIFICADO PARA PAGINACIÓN ===================
    public function getAllInformation(array $filters = [], $perPage = 10)
    {
        $query = User::with(['perfil', 'roles', 'status', 'documentType'])
            ->orderBy('id');

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

        if (!empty($filters['tipo_documento'])) {
            $query->whereHas('documentType', function ($q) use ($filters) {
                $q->where('acronym', $filters['tipo_documento']);
            });
        }

        $users = $query->paginate($perPage);

        return [
            "error" => false,
            "code" => 200,
            "message" => $users->isEmpty()
                ? "No hay usuarios registrados"
                : "Usuarios obtenidos con éxito",
            "data" => [
                "records" => $users->map(function ($user) {
                    // 💡 Protegemos cada relación
                    $roles = $user->roles ?? collect();
                    return [
                        'id' => $user->id,
                        'document_type_id' => $user->documentType->id ?? null,
                        'document_type' => $user->documentType->acronym ?? '',
                        'document' => $user->document,
                        'first_name' => $user->perfil->name ?? '',
                        'last_name' => $user->perfil->last_name ?? '',
                        'email' => $user->email,
                        'phone_number' => $user->perfil->phone ?? '',
                        'rol' => $roles->pluck('name')->implode(', '),
                        'estado' => $user->status->status ?? '',
                    ];
                }),
                "meta" => [
                    "current_page" => $users->currentPage(),
                    "last_page" => $users->lastPage(),
                    "per_page" => $users->perPage(),
                    "total" => $users->total(),
                ]
            ]
        ];
    }

    public function getAllApprentices(array $filters = [], $perPage = 10)
    {
        $query = User::with(['perfil', 'roles', 'status', 'fichas.programa'])
            ->whereHas('roles', function ($q) {
                $q->where('name', 'Aprendiz');
            })
            ->orderBy('id');

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

        if (!empty($filters['estados'])) {
            $query->whereHas('status', function ($q) use ($filters) {
                $q->where('name', $filters['estados']);
            });
        }

        if (!empty($filters['ficha'])) {
            $query->whereHas('ficha', function ($q) use ($filters) {
                $q->where('ficha', 'like', '%' . $filters['ficha'] . '%');
            });
        }

        if (!empty($filters['programa'])) {
            $query->whereHas('fichas.programa', function ($q) use ($filters) {
                $q->where('training_program', 'like', '%' . $filters['programa'] . '%');
            });
        }

        $apprentices = $query->paginate($perPage); // PAGINACIÓN

        $records = $apprentices->map(function ($user) {
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
        });

        return [
            "error" => false,
            "code" => 200,
            "message" => $apprentices->isEmpty()
                ? "No hay aprendices registrados"
                : "Aprendices obtenidos con éxito",
            "data" => [
                "records" => $records,
                "meta" => [
                    "current_page" => $apprentices->currentPage(),
                    "last_page" => $apprentices->lastPage(),
                    "per_page" => $apprentices->perPage(),
                    "total" => $apprentices->total(),
                ]
            ]
        ];
    }


    public function updateUser(array $data, $id)
    {
        try {
            DB::beginTransaction();

            $user = User::with(['perfil', 'roles', 'fichas'])->find($id);

            if (!$user) {
                return [
                    "error" => true,
                    "code" => 404,
                    "message" => "El usuario no existe",
                ];
            }

            // ================= USUARIO =================
            $user->update([
                'document'  => $data['documento'],
                'email'     => $data['correo'],
                'status_id' => $data['status_id'],
                'document_type_id' => $data['tipo_documento'],
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
                    $user->syncRoles([$rol->name]);
                }
            }

            // ================= FICHA (SOLO ASOCIAR) =================
            if (!empty($data['ficha_id'])) {

                $existe = ficha_user::where('usuario_id', $user->id)
                    ->where('ficha_id', $data['ficha_id'])
                    ->exists();

                if (!$existe) {
                    ficha_user::create([
                        'usuario_id' => $user->id,
                        'ficha_id'   => $data['ficha_id'],
                    ]);
                }
            }

            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Usuario actualizado correctamente",
                "data" => $user->load(['perfil', 'roles', 'fichas.programa'])
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                "error" => true,
                "code" => 500,
                "message" => "Error al actualizar el usuario",
            ];
        }
    }

    public function changePassword($data, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return [
                "error" => true,
                "code" => 404,
                "message" => "Usuario no encontrado"
            ];
        }

        // Verificar contraseña actual
        if (!Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['La contraseña actual es incorrecta']
            ]);
        }

        // Guardar nueva contraseña
        $user->password = Hash::make($data['new_password']);
        $user->save();

        return [
            "error" => false,
            "code" => 200,
            "message" => "Contraseña actualizada correctamente"
        ];
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

        //  EVITA QUE SE ELIMINE A SÍ MISMO
        if (auth()->id() === $user->id) {
            return [
                "error" => true,
                "code" => 403,
                "message" => "No puedes eliminar tu propio usuario",
            ];
        }

        // NO BORRAR SI TIENE ASISTENCIAS
        if ($user->assistances_count > 0) {
            return [
                "error" => true,
                "code" => 409,
                "message" => "No se puede eliminar el usuario porque tiene asistencias registradas",
            ];
        }

        try {
            DB::beginTransaction();

            // BORRAR PERFIL
            if ($user->perfil) {
                $user->perfil->delete();
            }

            // TABLA PIVOTE FICHAS
            $user->fichas()->detach();

            // ROLES (SPATIE)
            $user->roles()->detach();

            // USUARIO
            $user->delete();

            DB::commit();

            return [
                "error" => false,
                "code" => 200,
                "message" => "Usuario eliminado correctamente",
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                "error" => true,
                "code" => 500,
                "message" => $e->getMessage(),
            ];
        }
    }
}
