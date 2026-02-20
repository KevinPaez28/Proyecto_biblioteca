<?php

namespace Database\Seeders\Status_rooms;

use App\Models\StatesRooms\States_rooms;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class events_rooms extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        States_rooms::create(['name' => 'Activo']);
        States_rooms::create(['name' => 'Ocupado']);
        States_rooms::create(['name' => 'Desactiva']);  
    }
}
