<?php

namespace Database\Seeders\shifts;

use App\Models\Shifts\Shifts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class shiftSeeder extends Seeder
{
    
    public function run(): void
    {
        Shifts::create(['name' => 'Mañana']);
        Shifts::create(['name' => 'Tarde']);
        Shifts::create(['name' => 'Noche']);
    }
}
