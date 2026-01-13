<?php

namespace App\Models\UserstatusServices;

use Illuminate\Database\Eloquent\Model;

class user_statuses extends Model
{
    protected $table = "state_rooms";
    protected $fillable = ['name'];
}
