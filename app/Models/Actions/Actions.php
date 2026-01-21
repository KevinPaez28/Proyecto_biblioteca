<?php

namespace App\Models\Actions;

use App\Models\History\History;
use Illuminate\Database\Eloquent\Model;

class Actions extends Model
{
    protected $fillable = ['name'];

    public function histories()
    {
        return $this->hasMany(History::class, 'acction_id');
    }
}
