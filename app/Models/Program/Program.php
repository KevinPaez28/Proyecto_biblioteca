<?php

namespace App\Models\Program;

use App\Models\Ficha\Ficha;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Program extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'programs';
    protected $fillable = ['training_program'];

    public function fichas() {
        return $this->hasMany(Ficha::class);
    }
}
