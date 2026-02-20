<?php

namespace Database\Seeders\Reasons;

use App\Models\Reasons\reasons;
use Illuminate\Database\Seeder;

class reasonsSeeders extends Seeder
{
    public function run(): void
    {
        $reasons = [
            [
                'name' => 'Evento',
                'description' => 'Para seleccionar un evento, no eliminar',
                'state_reason_id' => 1
            ]
        ];

        foreach ($reasons as $reason) {
            reasons::create($reason);
        }
    }
}
