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
                'background' => '#020617',
                'surface' => '#0b1120',
                'surface_alt' => '#020617',
                'text' => '#f9fafb',
                'text_muted' => '#9ca3af',
                'accent' => '#fbbf24',
                'accent_text' => '#111827',
                'border' => '#1f2937',
            ],
            [
                'id' => 2,
                'name' => 'Extra kontraszt sötét',
                'value' => 'dark_high',
                'background' => '#000000',
                'surface' => '#020617',
                'surface_alt' => '#000000',
                'text' => '#ffffff',
                'text_muted' => '#e5e7eb',
                'accent' => '#facc15',
                'accent_text' => '#000000',
                'border' => '#facc15',
            ],
            [
                'id' => 3,
                'name' => 'Lágy sötét',
                'value' => 'soft_dark',
                'background' => '#020617',
                'surface' => '#020617',
                'surface_alt' => '#0f172a',
                'text' => '#e5e7eb',
                'text_muted' => '#94a3b8',
                'accent' => '#38bdf8',
                'accent_text' => '#0f172a',
                'border' => '#1e293b',
            ],
            [
                'id' => 4,
                'name' => 'Extra kontraszt világos',
                'value' => 'light_high',
                'background' => '#f9fafb',
                'surface' => '#ffffff',
                'surface_alt' => '#e5e7eb',
                'text' => '#020617',
                'text_muted' => '#4b5563',
                'accent' => '#1d4ed8',
                'accent_text' => '#ffffff',
                'border' => '#4b5563',
            ],
            [
                'id' => 5,
                'name' => 'Pasztell kontraszt',
                'value' => 'pastel',
                'background' => '#0f172a',
                'surface' => '#020617',
                'surface_alt' => '#111827',
                'text' => '#f9fafb',
                'text_muted' => '#cbd5f5',
                'accent' => '#f97316',
                'accent_text' => '#111827',
                'border' => '#f97316',
            ],
            [
                'id' => 6,
                'name' => 'Zöld-kék kontraszt',
                'value' => 'teal_contrast',
                'background' => '#020617',
                'surface' => '#022c22',
                'surface_alt' => '#0f172a',
                'text' => '#e5e7eb',
                'text_muted' => '#a7f3d0',
                'accent' => '#22c55e',
                'accent_text' => '#022c22',
                'border' => '#22c55e',
            ],
        ]);
    }
}
