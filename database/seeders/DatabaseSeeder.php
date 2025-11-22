<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('testing')) {
            $this->call(\Database\Seeders\TestSeeder::class);
        }
        
        $this->call(\Database\Seeders\RolesAndPermissionsSeeder::class);
        $this->call(\Database\Seeders\AdminUserSeeder::class);
        $this->call(\Database\Seeders\ColorPaletteSeeder::class);
    }
}
