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
        $administrador = Role::findByName('administrador');
        $instructor = Role::findByName('instructor');
        $aprendiz = Role::findByName('aprendiz');
        $ayudante = Role::findByName('ayudantes');

        $administrador->syncPermissions(Permission::all());
        $ayudante->syncPermissions([

            'users.index',
            'users.store',
            'users.update',

            'roles.index',
            'roles.store',

            'programs.index',

            'profiles.index',
      
            'fichas.index',
       
            'documents.index',

            'shifts.index',
            'shifts.store',
            'shifts.update',

            'reasons.index',
            'reasons.store',
            'reasons.update',

            'events.index',
            'events.today',
            'events.store',
            'events.update',

            'assistances.index',
            'assistances.store',
            'assistances.update',

            'rooms.index',
            'rooms.store',
            'rooms.update',
        ]);
    }
}
