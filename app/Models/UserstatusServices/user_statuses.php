<?php

namespace App\Models\UserstatusServices;

use Illuminate\Database\Eloquent\Model;

class user_statuses extends Model
{
    protected $table = "user_statuses";
    protected $fillable = ['status'];
}
