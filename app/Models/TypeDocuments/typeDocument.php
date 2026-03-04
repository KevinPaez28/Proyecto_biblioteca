<?php

namespace App\Models\TypeDocuments;

use Illuminate\Database\Eloquent\Model;

class typeDocument extends Model
{
    protected $table = 'document_types';

    protected $fillable = [
        'name',
        'acronym'
    ];
}
