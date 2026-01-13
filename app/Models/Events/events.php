<?php

namespace App\Models\Events;

use App\Models\Rooms\rooms;
use App\Models\StatesRooms\States_rooms;
use Illuminate\Database\Eloquent\Model;

class events extends Model
{
    protected $fillable = ['name', 'mandated', 'room_id', 'date', 'state_event_id'];

    public function state()
    {
        return $this->belongsTo(States_rooms::class, 'state_event_id');
    }
    public function room()
    {
        return $this->belongsTo(rooms::class, 'room_id');
    }
}
