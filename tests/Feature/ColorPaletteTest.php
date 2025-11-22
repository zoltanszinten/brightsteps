<?php

namespace Tests\Feature;

use App\Models\ColorPalette;
use App\Models\User;
use Database\Seeders\TestSeeder;
use Tests\TestCase;

class ColorPaletteTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestSeeder::class);
    }

    public function test_index_requires_auth()
    {
        $response = $this->getJson('/api/color-palettes');
        $response->assertStatus(401);
    }

    public function test_index_returns_color_palettes()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        ColorPalette::factory()->create(['id' => 2]);

        $response = $this->getJson('/api/color-palettes');

        $response->assertStatus(200);
    }

    public function test_details_requires_auth()
    {
        $response = $this->getJson('/api/color-palettes/1');
        $response->assertStatus(401);
    }

    public function test_details_returns_single_palette()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $palette = ColorPalette::factory()->create();

        $response = $this->getJson('/api/color-palettes/' . $palette->id);

        $response->assertStatus(200);
    }
}
