<?php

namespace App\Models\assitances;

use Illuminate\Database\Eloquent\Model;

class assitances extends Model
{
    protected $table = "assistances";
    protected $fillable = ["user_id","working_day_id","reason_id","event_id"];
}
