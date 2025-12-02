<?php

namespace App\Models\Permissions;

use Illuminate\Database\Eloquent\Model;

class permission extends Model
{
    protected $fillable = [
        'name',
        'guard_name',
        'descripcion',
      ];                                                                                                                                                                                                                                                               
}
