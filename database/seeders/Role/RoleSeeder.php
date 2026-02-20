<?php

namespace Database\Seeders\Role;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Administrador']);
        Role::create(['name' => 'Aprendiz']);
        Role::create(['name' => 'Instructor']);
        Role::create(['name' => 'Egresado']);
        Role::create(['name' => 'Apoyo']);
        Role::create(['name' => 'Visitante']);
    }
}
