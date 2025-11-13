<?php

namespace App\Http\Controllers;

use App\Models\ColorPalette;

class ColorPaletteController extends Controller
{
    public function index()
    {
        return ColorPalette::select('id', 'name', 'value')
            ->orderBy('id')
            ->get();
    }
}
