<?php

namespace App\Models\Reasons;

use App\Models\assitances\assitances;
use App\Models\Reason_estates\Reason_estates;
use Illuminate\Database\Eloquent\Model;

class reasons extends Model
{
    protected $fillable = ['name', 'description', 'state_reason_id'];

    public function state()
    {
        return $this->belongsTo(Reason_estates::class, 'state_reason_id');
    }
    public function assistances()
    {
        return $this->hasMany(assitances::class, 'reason_id');
    }
}
