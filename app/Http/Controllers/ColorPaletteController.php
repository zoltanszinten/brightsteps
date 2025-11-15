<?php

namespace App\Http\Controllers;

use App\Models\ColorPalette;

class ColorPaletteController extends Controller
{
    public function index()
    {
        return ColorPalette::query()
            ->orderBy('id')
            ->get();
    }
}
