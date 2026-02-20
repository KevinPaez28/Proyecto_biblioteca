<?php

namespace App\Models\History;

use App\Models\Actions\Actions;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'Histories';

    protected $fillable = ['usuario_id', 'acction_id', 'description', 'model_id', 'model_type', 'creation_date'];

    public function action()
    {
        return $this->belongsTo(Actions::class, 'acction_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
