<?php

namespace Database\Seeders\Permissions;

use App\Models\Permissions\permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            ['name' => 'user-status.index', 'description' => 'Ver estados de usuario'],
            ['name' => 'user-status.store', 'description' => 'Crear estado de usuario'],
            ['name' => 'user-status.update', 'description' => 'Actualizar estado de usuario'],
            ['name' => 'user-status.destroy', 'description' => 'Eliminar estado de usuario'],

            // ================= USUARIOS =================
            ['name' => 'users.index', 'description' => 'Ver listado de usuarios'],
            ['name' => 'users.search', 'description' => 'Buscar usuarios'],
            ['name' => 'users.store', 'description' => 'Crear usuario'],
            ['name' => 'users.update', 'description' => 'Actualizar usuario'],
            ['name' => 'users.destroy', 'description' => 'Eliminar usuario'],

            // ================= ROLES =================
            ['name' => 'roles.index', 'description' => 'Ver listado de roles'],
            ['name' => 'roles.store', 'description' => 'Crear rol'],
            ['name' => 'roles.update', 'description' => 'Actualizar rol'],
            ['name' => 'roles.destroy', 'description' => 'Eliminar rol'],

            // ================= PROGRAMAS =================
            ['name' => 'programs.index', 'description' => 'Ver programas'],
            ['name' => 'programs.store', 'description' => 'Crear programa'],
            ['name' => 'programs.update', 'description' => 'Actualizar programa'],
            ['name' => 'programs.destroy', 'description' => 'Eliminar programa'],

            // ================= PERFILES =================
            ['name' => 'profiles.index', 'description' => 'Ver perfiles'],
            ['name' => 'profiles.store', 'description' => 'Crear perfil'],
            ['name' => 'profiles.update', 'description' => 'Actualizar perfil'],
            ['name' => 'profiles.destroy', 'description' => 'Eliminar perfil'],

            // ================= FICHAS =================
            ['name' => 'fichas.index', 'description' => 'Ver fichas'],
            ['name' => 'fichas.store', 'description' => 'Crear ficha'],
            ['name' => 'fichas.update', 'description' => 'Actualizar ficha'],
            ['name' => 'fichas.destroy', 'description' => 'Eliminar ficha'],

            // ================= DOCUMENTOS =================
            ['name' => 'documents.index', 'description' => 'Ver documentos'],
            ['name' => 'documents.store', 'description' => 'Crear documento'],
            ['name' => 'documents.update', 'description' => 'Actualizar documento'],
            ['name' => 'documents.destroy', 'description' => 'Eliminar documento'],

            // ================= ACCIONES =================
            ['name' => 'actions.index', 'description' => 'Ver acciones'],
            ['name' => 'actions.store', 'description' => 'Crear acción'],
            ['name' => 'actions.update', 'description' => 'Actualizar acción'],
            ['name' => 'actions.destroy', 'description' => 'Eliminar acción'],

            // ================= HISTORIAL =================
            ['name' => 'history.index', 'description' => 'Ver historial'],
            ['name' => 'history.store', 'description' => 'Crear historial'],
            ['name' => 'history.update', 'description' => 'Actualizar historial'],
            ['name' => 'history.destroy', 'description' => 'Eliminar historial'],

            // ================= HORARIOS =================
            ['name' => 'schedules.index', 'description' => 'Ver horarios'],
            ['name' => 'schedules.store', 'description' => 'Crear horario'],
            ['name' => 'schedules.update', 'description' => 'Actualizar horario'],
            ['name' => 'schedules.destroy', 'description' => 'Eliminar horario'],

            // ================= JORNADAS =================
            ['name' => 'shifts.index', 'description' => 'Ver jornadas'],
            ['name' => 'shifts.store', 'description' => 'Crear jornada'],
            ['name' => 'shifts.update', 'description' => 'Actualizar jornada'],
            ['name' => 'shifts.destroy', 'description' => 'Eliminar jornada'],

            // ================= MOTIVOS =================
            ['name' => 'reasons.index', 'description' => 'Ver motivos'],
            ['name' => 'reasons.store', 'description' => 'Crear motivo'],
            ['name' => 'reasons.update', 'description' => 'Actualizar motivo'],
            ['name' => 'reasons.destroy', 'description' => 'Eliminar motivo'],

            // ================= EVENTOS =================
            ['name' => 'events.index', 'description' => 'Ver eventos'],
            ['name' => 'events.today', 'description' => 'Ver eventos de hoy'],
            ['name' => 'events.store', 'description' => 'Crear evento'],
            ['name' => 'events.update', 'description' => 'Actualizar evento'],
            ['name' => 'events.destroy', 'description' => 'Eliminar evento'],

            // ================= ASISTENCIAS =================
            ['name' => 'assistances.index', 'description' => 'Ver asistencias'],
            ['name' => 'assistances.store', 'description' => 'Registrar asistencia'],
            ['name' => 'assistances.update', 'description' => 'Actualizar asistencia'],
            ['name' => 'assistances.destroy', 'description' => 'Eliminar asistencia'],

            // ================= SALAS =================
            ['name' => 'rooms.index', 'description' => 'Ver salas'],
            ['name' => 'rooms.store', 'description' => 'Crear sala'],
            ['name' => 'rooms.update', 'description' => 'Actualizar sala'],
            ['name' => 'rooms.destroy', 'description' => 'Eliminar sala'],
        ];

        foreach ($permissions as $permission) {
            permission::create([
                'name' => $permission['name'],
                'description' => $permission['description']
            ]);
        }
    }
}
