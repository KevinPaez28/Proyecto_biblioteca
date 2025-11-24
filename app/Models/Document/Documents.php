<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;

    // Campos que se pueden llenar con $document->update([...]) o create([...])
    protected $fillable = [
        'users_id',
        'title',
        'path',
        'extension',
        'type',
        'size',
    ];
}
