<?php

namespace App\Models\StatesRooms;

use App\Models\Rooms\rooms;
use Illuminate\Database\Eloquent\Model;

class States_rooms extends Model
{
    protected $table = "state_rooms";
    protected $fillable = ['name'];

      public function rooms()
    {
        return $this->hasMany(rooms::class, 'state_room_id');
    }
}
