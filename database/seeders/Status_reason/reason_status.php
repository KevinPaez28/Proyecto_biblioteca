<?php

namespace Database\Seeders\Status_reason;

use App\Models\Reason_estates\Reason_estates;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class reason_status extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reason_estates::create(['name' => 'Activo']);
        Reason_estates::create(['name' => 'Desactivo']);       
    }
}
