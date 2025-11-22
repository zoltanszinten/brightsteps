<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ColorPaletteFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => 'Test',
            'value' => 'test',
            'background' => '#000',
            'surface' => '#000',
            'surface_alt' => '#000',
            'text' => '#fff',
            'text_muted' => '#ccc',
            'accent' => '#f00',
            'accent_text' => '#fff',
            'border' => '#333',
        ];
    }
}
