<?php

namespace Tests\Feature\Image;

use App\Models\Image;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Constants\AuthMethod;
use Tests\MainTenantApiTest;
use Tests\Traits\RefreshTenantDatabase;

class ImageTest extends MainTenantApiTest
{
    // use RefreshTenantDatabase;
    use RefreshDatabase;

    protected $tenancy = true;

    // protected function setUp(): void
    // {
    //     parent::setUp();
    //     $this->refreshTenantDatabase();
    // }
    // protected function tearDown(): void
    // {
    //     $this->refreshTenantDatabase();
    //     parent::tearDown();
    // }

    public function testIndex()
    {
        Image::factory(3)->create();

        $this->setAuthNewGuest();
        $response = $this->api('GET', '/images');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'property_id', 'url', 'description', 'created_at', 'updated_at'],
            ],
        ]);
        $response->assertJsonCount(3, 'data');
    }

    public function testStore()
    {
        $data = [
            'description' => 'Test Image',
            'property_id' => Property::factory()->create()->id,
            'url' => 'https://via.placeholder.com/150',
        ];

        $response = $this->assertCreation(AuthMethod::ADMIN, '/images', $data, 201);
        $response->assertJsonStructure(['data' => ['id', 'url', 'description', 'created_at', 'updated_at']]);
        $response->assertJson(['data' => ['description' => $data['description']]]);
        $this->assertDatabaseHas('images', ['description' => $data['description']]);

        $this->assertCreation(AuthMethod::MANAGER, '/images', [
            'description' => 'Test Image 2',
            'property_id' => Property::factory()->create()->id,
            'url' => 'https://via.placeholder.com/150',
        ], 201);

        $this->assertCreation(AuthMethod::ASSISTANT, '/images', [
            'description' => 'Test Image 3',
            'property_id' => Property::factory()->create()->id,
            'url' => 'https://via.placeholder.com/150',
        ], 403);

        $this->assertCreation(AuthMethod::GUEST, '/images', [
            'description' => 'Test Image 4',
            'property_id' => Property::factory()->create()->id,
            'url' => 'https://via.placeholder.com/150',
        ], 403);
    }

    public function testShow()
    {
        $image = Image::factory()->create();

        $response = $this->assertRead(AuthMethod::GUEST, "/images/{$image->id}", 200);
        $response->assertJsonStructure(['data' => ['id', 'url', 'description', 'created_at', 'updated_at']]);
        $response->assertJson([
            'data' => [
                'id' => $image->id,
                'url' => $image->url,
                'description' => $image->description,
            ],
        ]);
    }

    public function testUpdate()
    {
        $image = Image::factory()->create();
        $data = [
            'description' => 'Updated Image',
        ];

        $response = $this->assertUpdate(AuthMethod::ADMIN, "/images/{$image->id}", $data, 200);
        $response->assertJsonStructure(['data' => ['id', 'url', 'description', 'created_at', 'updated_at']]);
        $response->assertJson([
            'data' => [
                'id' => $image->id,
                'description' => $data['description'],
            ],
        ]);
        $this->assertDatabaseHas('images', $data);

        $this->assertUpdate(AuthMethod::MANAGER, "/images/{$image->id}", $data, 200);
        $this->assertUpdate(AuthMethod::ASSISTANT, "/images/{$image->id}", $data, 200);
        $this->assertUpdate(AuthMethod::GUEST, "/images/{$image->id}", $data, 403);
    }

    public function testDestroy()
    {
        $image = Image::factory()->create();
        $this->assertDelete(AuthMethod::ADMIN, "/images/{$image->id}", 204);
        $this->assertDatabaseMissing('images', ['id' => $image->id]);

        $image = Image::factory()->create();
        $this->assertDelete(AuthMethod::MANAGER, "/images/{$image->id}", 403);
        $this->assertDelete(AuthMethod::ASSISTANT, "/images/{$image->id}", 403);
        $this->assertDelete(AuthMethod::GUEST, "/images/{$image->id}", 403);
        $this->assertDatabaseHas('images', ['id' => $image->id]);
    }

    public function testShowNonExistentImage()
    {
        $this->assertRead(AuthMethod::GUEST, '/images/999999', 404);
    }

    public function testUpdateNonExistentImage()
    {
        $data = [
            'description' => 'Non Existent Image',
        ];

        $this->assertUpdate(AuthMethod::MANAGER, '/images/999999', $data, 404);
    }

    public function testDestroyNonExistentImage()
    {
        $this->assertDelete(AuthMethod::ADMIN, '/images/999999', 404);
    }
}
