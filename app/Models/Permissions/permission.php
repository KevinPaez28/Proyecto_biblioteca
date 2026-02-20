<?php

namespace App\Models\Permissions;

use Spatie\Permission\Models\Permission as SpatiePermission;


class permission extends SpatiePermission
{
  protected $fillable = [
    'name',
    'guard_name',
    'descripcion',
  ];
}
