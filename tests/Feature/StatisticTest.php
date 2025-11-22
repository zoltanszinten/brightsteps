<?php

namespace Tests\Feature;

use App\Models\Statistic;
use App\Models\User;
use Database\Seeders\TestSeeder;
use Tests\TestCase;

class StatisticTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestSeeder::class);
    }

    public function test_store_requires_auth()
    {
        $response = $this->postJson('/api/statistics', []);
        $response->assertStatus(401);
    }

    public function test_store_creates_statistic()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/statistics', [
            'type' => 'test',
            'points' => 10,
            'time' => 100
        ]);

        $response->assertStatus(201);
    }

    public function test_index_requires_admin()
    {
        $child = User::factory()->create(['type' => 'token']);

        $user = User::factory()->create(); // not admin
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/statistics/' . $child->id);

        $response->assertStatus(403);
    }

    public function test_index_returns_statistics_for_child()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $child = User::factory()->create(['type' => 'token']);

        Statistic::create([
            'user_id' => $child->id,
            'type' => 'test',
            'points' => 20,
            'time' => 200
        ]);

        $this->actingAs($admin, 'sanctum');

        $response = $this->getJson('/api/statistics/' . $child->id);

        $response->assertStatus(200);
    }
}
