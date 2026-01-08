<?php

namespace App\Models\assitances;

use App\Models\Events\events;
use App\Models\Reasons\reasons;
use App\Models\Schedules\Schedules;
use App\Models\Shifts\Shifts;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class assitances extends Model
{
    protected $table = "assistances";
    protected $fillable = ["user_id", "working_day_id", "reason_id", "event_id"];

    public function user()
    {
        return $this->belongsTo(User::class); // Cada asistencia pertenece a un usuario
    }

    public function shift()
    {
        return $this->belongsTo(Shifts::class, 'working_day_id'); // Cada asistencia pertenece a una jornada
    }
     public function workingDay()
     {
         return $this->belongsTo(Shifts::class, 'working_day_id');
     }
 
     public function reason()
     {
         return $this->belongsTo(reasons::class, 'reason_id');
     }

    public function schedule()
    {
        return $this->belongsTo(Schedules::class); 
    }
    public function event()
    {
        return $this->belongsTo(events::class, 'event_id');
    }

    
}
