<?php

namespace App\Models\Profiles;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    protected $fillable = ['usuario_id', 'name', 'last_name', 'phone', 'email'];

    public function user()
{
    return $this->belongsTo(User::class, 'usuario_id');
}

}
