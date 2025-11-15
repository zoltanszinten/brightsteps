<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserSetting extends Authenticatable
{
    protected $fillable = [
        'user_id',
        'width',
        'font_size',
        'letter_spacing',
        'line_height',
        'color_palette_id',
        'show_card_game',
        'show_recognition_game',
        'show_map_game',
    ];

    protected $casts = [
        'show_card_game' => 'boolean',
        'show_recognition_game' => 'boolean',
        'show_map_game' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function colorPalette()
    {
        return $this->belongsTo(ColorPalette::class);
    }
}
