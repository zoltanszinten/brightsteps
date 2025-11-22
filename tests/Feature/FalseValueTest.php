<?php

namespace Tests\Feature;

use App\Models\FalseValue;
use App\Models\User;
use Database\Seeders\TestSeeder;
use Tests\TestCase;

class FalseValueTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestSeeder::class);
    }

    public function test_index_requires_auth()
    {
        $response = $this->getJson('/api/false-values');
        $response->assertStatus(401);
    }

    public function test_index_returns_items()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        FalseValue::create(['value' => 'test']);

        $response = $this->getJson('/api/false-values');

        $response->assertStatus(200);
    }

    public function test_store_requires_admin()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/false-values', [
            'value' => 'test'
        ]);

        $response->assertStatus(403);
    }

    public function test_store_creates_value()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'sanctum');

        $response = $this->postJson('/api/false-values', [
            'value' => 'something'
        ]);

        $response->assertStatus(201);
    }

    public function test_destroy_requires_admin()
    {
        $item = FalseValue::create(['value' => 'delete-me']);

        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->deleteJson('/api/false-values/' . $item->id);

        $response->assertStatus(403);
    }

    public function test_destroy_removes_value()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'sanctum');

        $item = FalseValue::create(['value' => 'remove-me']);

        $response = $this->deleteJson('/api/false-values/' . $item->id);

        $response->assertStatus(200);
    }
}
