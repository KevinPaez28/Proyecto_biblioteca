<?php

namespace App\Models\Reasons;

use Illuminate\Database\Eloquent\Model;

class reasons extends Model
{
 protected $fillable = ['name','description','state_reason_id'];
}
