<?php

namespace App\Http\Controllers;

use App\Models\ColorPalette;

class ColorPaletteController extends Controller
{
    public function index()
    {
        return response()->json(ColorPalette::query()->orderBy('id')->get());
    }

    public function details(ColorPalette $colorPalette)
    {
        return response()->json($colorPalette);
    }
}
