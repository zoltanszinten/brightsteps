<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    protected $table = 'statistics';

    protected $fillable = [
        'user_id',
        'card_point',
        'recognition_point',
        'map_point',
        'card_time',
        'recognition_time',
        'map_time',
        'is_finished',
    ];

    protected $casts = [
        'card_point' => 'integer',
        'recognition_point' => 'integer',
        'map_point' => 'integer',
        'card_time' => 'integer',
        'recognition_time' => 'integer',
        'map_time' => 'integer',
        'is_finished' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
