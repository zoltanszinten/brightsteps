<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorPaletteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('color_palettes')->insert([
            [
                'id' => 1,
                'name' => 'Alap',
                'value' => 'default',
            ],
            [
                'id' => 2,
                'name' => 'Extra kontraszt',
                'value' => 'contrast',
            ],
            [
                'id' => 3,
                'name' => 'Lágy',
                'value' => 'soft',
            ],
        ]);
    }
}
