<?php

namespace App\Models\Reason_estates;

use App\Models\Reasons\reasons;
use Illuminate\Database\Eloquent\Model;

class Reason_estates extends Model
{
    protected $table = "states_Reason";
    protected $fillable = ['name'];

    public function reasons()
    {
        return $this->hasMany(reasons::class);
    }
}
