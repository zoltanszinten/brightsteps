<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class ColorPalette extends Authenticatable
{
    protected $fillable = [
        'name',
        'value',
    ];

    public function userSettings()
    {
        return $this->hasMany(UserSetting::class, 'color_palette_id');
    }
}
