<?php

namespace App\Models\Shifts;

use App\Models\assitances\assitances;
use App\Models\Schedules\Schedules;
use Illuminate\Database\Eloquent\Model;

class Shifts extends Model
{
        protected $fillable = ['name', 'schedules_id'];
        public function schedule()
    {
        return $this->belongsTo(Schedules::class, 'schedules_id');
    }

    public function assistances()
    {
        return $this->hasMany(assitances::class, 'working_day_id');
    }
}
