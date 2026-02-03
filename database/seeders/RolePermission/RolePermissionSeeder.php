<?php

namespace Database\Seeders\RolePermission;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $administrador = Role::findByName('Administrador');
        $instructor = Role::findByName('Instructor');
        $aprendiz = Role::findByName('Aprendiz');
        $Apoyo = Role::findByName('Apoyo');

        $administrador->syncPermissions(Permission::all());
        $Apoyo->syncPermissions([
            'auth.login',
            // ESTADO USUARIOS
            'user-status.index',
            'user-status.store',
            'user-status.update',
            'user-status.destroy',

            // ESTADOS EVENTOS
            'events.index',       // para ver
            'events.store',       // para crear
            'events.update',      // para actualizar
            'events.destroy',     // para eliminar

            // ESTADOS SALAS
            'rooms.index',        // para ver
            'rooms.store',        // para crear
            'rooms.update',       // para actualizar
            'rooms.destroy',      // para eliminar

            'history.index',
            'history.store',
            'history.update',
            'history.destroy',

            'document.index',
            'document.store',
            'document.update',
            'document.destroy',

            // PROGRAMAS
            'programs.index',
            'programs.store',
            'programs.update',
            'programs.destroy',

            // FICHAS
            'fichas.index',
            'fichas.store',
            'fichas.update',
            'fichas.destroy',

            // EVENTOS
            'events.index',
            'events.today',      // Ver eventos de hoy
            'events.store',
            'events.update',
            'events.destroy',
        ]);
    }
}
