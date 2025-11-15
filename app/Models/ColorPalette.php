<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ColorPalette extends Authenticatable
{
    protected $fillable = [
        'name',
        'value',
        'background',
        'surface',
        'surface_alt',
        'text',
        'text_muted',
        'accent',
        'accent_text',
        'border',
    ];

    public function userSettings()
    {
        return $this->hasMany(UserSetting::class, 'color_palette_id');
    }
}
