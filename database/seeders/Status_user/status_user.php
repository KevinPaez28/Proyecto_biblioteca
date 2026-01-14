<?php

namespace Database\Seeders\Status_user;

use App\Models\UserstatusServices\user_statuses;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class status_user extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        user_statuses::create(['status' => 'Activo']);
        user_statuses::create(['status' => 'Desactivo']);  
    }
}
