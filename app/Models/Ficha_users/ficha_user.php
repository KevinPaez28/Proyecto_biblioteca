<?php

namespace App\Models\Ficha_users;

use Illuminate\Database\Eloquent\Model;

class ficha_user extends Model
{
    protected $table = 'ficha_user'; // nombre exacto de tu tabla
    protected $fillable = ['usuario_id','ficha_id'];
}
