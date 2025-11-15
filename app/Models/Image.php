<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'type',
        'value',
        'path',
        'original_name',
        'size',
        'mime_type',
    ];
}
