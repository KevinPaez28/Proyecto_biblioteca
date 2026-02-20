<?php

namespace App\Models\Schedules;

use App\Models\assitances\assitances;
use App\Models\Shifts\Shifts;
use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    protected $fillable = ['name', 'start_time', 'end_time'];


    // Schedules.php
    public function shifts()
    {
        return $this->hasMany(Shifts::class, 'schedules_id');
    }
}
