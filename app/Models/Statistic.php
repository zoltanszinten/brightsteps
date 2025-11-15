<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'points',
        'time',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
