<?php

namespace Database\Seeders\Status_events;

use App\Models\State_events\state_events;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class events_status extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        state_events::create(['name' => 'Activo']);
        state_events::create(['name' => 'Cancelado']);  
    }
}
