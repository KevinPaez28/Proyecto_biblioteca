<?php

namespace App\Models\Perfiles;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    protected $fillable = ['user_id', 'name', 'last_name', 'phone', 'email', 'ficha_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
