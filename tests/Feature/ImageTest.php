<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\User;
use Database\Seeders\TestSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TestSeeder::class);
    }

    public function test_index_requires_auth()
    {
        $response = $this->getJson('/api/images');
        $response->assertStatus(401);
    }

    public function test_index_returns_images()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Image::create([
            'type' => 'card',
            'value' => 'test',
            'path' => 'images/a.png',
            'original_name' => 'a.png',
            'size' => 100,
            'mime_type' => 'image/png'
        ]);

        $response = $this->getJson('/api/images');
        $response->assertStatus(200);
    }

    public function test_store_requires_admin()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        Storage::fake('public');

        $response = $this->postJson('/api/images', [
            'type' => 'card',
            'value' => 'something',
            'image' => UploadedFile::fake()->create('test.png', 100, 'image/png'),
        ]);

        $response->assertStatus(403);
    }

    public function test_store_uploads_image()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'sanctum');

        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.png', 200, 'image/png');

        $response = $this->postJson('/api/images', [
            'type' => 'card',
            'value' => 'test-value',
            'image' => $file,
        ]);

        $response->assertStatus(201);
    }

    public function test_destroy_requires_admin()
    {
        $image = Image::create([
            'type' => 'card',
            'value' => 't',
            'path' => 'images/x.png',
            'original_name' => 'x.png',
            'size' => 10,
            'mime_type' => 'image/png'
        ]);

        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->deleteJson('/api/images/' . $image->id);

        $response->assertStatus(403);
    }

    public function test_destroy_deletes_image()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin, 'sanctum');

        Storage::fake('public');

        $image = Image::create([
            'type' => 'card',
            'value' => 't',
            'path' => 'images/to-delete.png',
            'original_name' => 'to-delete.png',
            'size' => 10,
            'mime_type' => 'image/png'
        ]);

        $response = $this->deleteJson('/api/images/' . $image->id);

        $response->assertStatus(200);
    }
}
