<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\TestSeeder;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestSeeder::class);
    }

    public function test_login_returns_token_on_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function test_login_fails_on_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrong',
        ]);

        $response->assertStatus(401);
    }

    public function test_logout_requires_auth()
    {
        $response = $this->postJson('/api/logout');
        $response->assertStatus(401);
    }

    public function test_logout_works_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/logout');
        $response->assertStatus(200);
    }
}
