<?php

namespace App\Models\Rooms;

use App\Models\Events\events;
use App\Models\StatesRooms\States_rooms;
use Illuminate\Database\Eloquent\Model;

class rooms extends Model
{
    protected $fillable = ['name', 'description', 'state_room_id'];

    public function state()
    {
        return $this->belongsTo(States_rooms::class, 'state_room_id');
    }
    public function events()
    {
        return $this->hasMany(events::class, 'room_id');
    }
}
