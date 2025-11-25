<?php

namespace App\Models\History;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'history';

    protected $fillable = ['usuario_id', 'acction_id', 'description','model_id', 'model_type', 'creation_date'];
}
