<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

class events extends Model
{
    protected $fillable = ['name','mandated','room_id','date','state_event_id'];
}
