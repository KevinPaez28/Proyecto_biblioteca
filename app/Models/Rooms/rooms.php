<?php

namespace App\Models\Rooms;

use Illuminate\Database\Eloquent\Model;

class rooms extends Model
{
    protected $fillable = ['name','description','state_room_id'];
}
