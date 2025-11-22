<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\TestSeeder;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestSeeder::class);
    }

    public function test_user_requires_auth()
    {
        $response = $this->getJson('/api/user');
        $response->assertStatus(401);
    }

    public function test_user_returns_data_when_authenticated()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');
        $response = $this->getJson('/api/user');

        $response->assertStatus(200);
    }

    public function test_child_create_requires_auth()
    {
        $response = $this->postJson('/api/child', []);
        $response->assertStatus(401);
    }

    public function test_child_create_valid()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'sanctum');

        $response = $this->postJson('/api/child', [
            'name' => 'Child Name'
        ]);

        $response->assertStatus(201);
    }

    public function test_child_detail_non_existing_returns_404()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/child/99999');
        $response->assertStatus(404);
    }

    public function test_child_delete_basic()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $child = User::factory()->create(['type' => 'token']);
        $child->setting()->create([
            'width' => 1280,
            'font_size' => 18,
            'letter_spacing' => 4,
            'line_height' => 160,
            'color_palette_id' => 1,
            'show_card_game' => true,
            'show_recognition_game' => true,
            'show_map_game' => true,
        ]);

        $this->actingAs($admin, 'sanctum');

        $response = $this->deleteJson('/api/child/' . $child->id);

        $response->assertStatus(200);
    }
}
