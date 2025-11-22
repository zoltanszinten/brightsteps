<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class TestSeeder extends Seeder
{
    public function run()
    {
        Role::firstOrCreate(['name' => 'admin']);

        DB::table('color_palettes')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'Test',
                'value' => 'test',
                'background' => '#000000',
                'surface' => '#000000',
                'surface_alt' => '#000000',
                'text' => '#ffffff',
                'text_muted' => '#cccccc',
                'accent' => '#ff0000',
                'accent_text' => '#ffffff',
                'border' => '#333333',
            ]
        );
    }
}
