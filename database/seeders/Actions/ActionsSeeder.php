<?php

namespace Database\Seeders\Actions;

use App\Models\Actions\Actions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Actions::create(['name' => 'Crear']);
        Actions::create(['name' => 'Editar']);
        Actions::create(['name' => 'Eliminar']);

    }
}
