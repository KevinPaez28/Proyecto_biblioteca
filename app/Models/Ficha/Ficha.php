<?php

namespace App\Models\Ficha;

use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    protected $table = 'ficha'; 
    protected $fillable = ['nombre', 'programa_id'];
}
