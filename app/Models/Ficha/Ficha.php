<?php

namespace App\Models\Ficha;

use App\Models\Ficha_users\ficha_user;
use App\Models\Program\Program;
use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    protected $table = 'ficha'; 
    protected $fillable = ['ficha', 'program_id'];

     // Una ficha puede estar vinculada a varios usuarios
     public function usuarios()
     {
         return $this->hasMany(ficha_user::class, 'ficha_id');
     }
 
     // Relación con programa
     public function programa()
     {
         return $this->belongsTo(Program::class, 'program_id');
     }
}
