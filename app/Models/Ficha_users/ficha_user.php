<?php

namespace App\Models\Ficha_users;

use App\Models\Ficha\Ficha;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class ficha_user extends Model
{
    protected $table = 'ficha_user'; 

    protected $fillable = ['usuario_id', 'ficha_id'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con Ficha
    public function ficha()
    {
        return $this->belongsTo(Ficha::class, 'ficha_id');
    }
}
