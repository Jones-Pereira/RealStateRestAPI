<?php

namespace Tests\Feature\Property;

use App\Models\City;
use App\Models\Property;
use Tests\Constants\AuthMethod;
use Tests\MainTenantApiTest;

class PropertyTest extends MainTenantApiTest
{
    protected $tenancy = true;

    public function testIndex()
    {
        Property::factory(3)->create();

        $this->setAuthNewGuest();
        $response = $this->api('GET', '/properties');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'address',
                    'price',
                    'description',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        $response->assertJsonCount(3, 'data');
    }

    public function testStore()
    {
        $data = [
            'title' => 'Property Title',
            'description' => 'Property Description',
            'price' => 100000,
            'address' => 'Property Address',
            'city_id' => City::factory()->create()->id,
            'zip_code' => '12345',
            'type' => 'type',
            'status' => 'status',
        ];

        $response = $this->assertCreation(AuthMethod::ADMIN, '/properties', $data, 201);
        $response->assertJsonStructure(['data' => [
            'id',
            'description',
            'price',
            'address',
            'city_id',
            'zip_code',
            'type',
            'status',
        ]]);

        $response->assertJson(['data' => $data]);
        $this->assertDatabaseHas('properties', $data);

        $this->assertCreation(AuthMethod::MANAGER, '/properties', [
            'title' => 'Property Title 2',
            'description' => 'Property Description 2',
            'price' => 200000,
            'address' => 'Property Address 2',
            'city_id' => City::factory()->create()->id,
            'zip_code' => '54321',
            'type' => 'type2',
            'status' => 'status2',
        ], 201);

        $this->assertCreation(AuthMethod::ASSISTANT, '/properties', [
            'title' => 'Property Title 3',
            'description' => 'Property Description 3',
            'price' => 300000,
            'address' => 'Property Address 3',
            'city_id' => City::factory()->create()->id,
            'zip_code' => '67890',
            'type' => 'type3',
            'status' => 'status3',
        ], 403);

        $this->assertCreation(AuthMethod::GUEST, '/properties', [
            'title' => 'Property Title 4',
            'description' => 'Property Description 4',
            'price' => 400000,
            'address' => 'Property Address 4',
            'city_id' => City::factory()->create()->id,
            'zip_code' => '09876',
            'type' => 'type4',
            'status' => 'status4',
        ], 403);
    }

    public function testShow()
    {
        $property = Property::factory()->create();

        $response = $this->assertRead(AuthMethod::GUEST, "/properties/{$property->id}", 200);
        $response->assertJsonStructure(['data' => [
            'id',
            'description',
            'price',
            'address',
            'city_id',
            'zip_code',
            'type',
            'status',
            'created_at',
            'updated_at',
        ]]);
        $response->assertJson([
            'data' => [
                'id' => $property->id,
                'description' => $property->description,
                'price' => $property->price,
                'address' => $property->address,
                'city_id' => $property->city_id,
                'zip_code' => $property->zip_code,
                'type' => $property->type,
                'status' => $property->status,
            ],
        ]);
    }

    public function testUpdate()
    {
        $property = Property::factory()->create();
        $data = [
            'title' => 'Updated Property',
            'price' => 200000.00,
        ];

        $response = $this->assertUpdate(AuthMethod::ADMIN, "/properties/{$property->id}", $data, 200);
        $response->assertJsonStructure(['data' => [
            'id',
            'description',
            'price',
            'address',
            'city_id',
            'zip_code',
            'type',
            'status',
            'created_at',
            'updated_at',
        ]]);
        $response->assertJson([
            'data' => [
                'id' => $property->id,
                'description' => $property->description,
                'price' => $data['price'],
                'title' => $data['title'],
                'address' => $property->address,
                'city_id' => $property->city_id,
                'zip_code' => $property->zip_code,
                'type' => $property->type,
                'status' => $property->status,
            ],
        ]);
        $this->assertDatabaseHas('properties', $data);

        $this->assertUpdate(AuthMethod::MANAGER, "/properties/{$property->id}", $data, 200);
        $this->assertUpdate(AuthMethod::ASSISTANT, "/properties/{$property->id}", $data, 200);
        $this->assertUpdate(AuthMethod::GUEST, "/properties/{$property->id}", $data, 403);
    }

    public function testDestroy()
    {
        $property = Property::factory()->create();
        $this->assertDelete(AuthMethod::ADMIN, "/properties/{$property->id}", 204);
        $this->assertDatabaseMissing('properties', ['id' => $property->id]);

        $property = Property::factory()->create();
        $this->assertDelete(AuthMethod::MANAGER, "/properties/{$property->id}", 403);
        $this->assertDelete(AuthMethod::ASSISTANT, "/properties/{$property->id}", 403);
        $this->assertDelete(AuthMethod::GUEST, "/properties/{$property->id}", 403);

        $this->assertDatabaseHas('properties', ['id' => $property->id]);
    }

    public function testShowNonExistentProperty()
    {
        $this->assertRead(AuthMethod::GUEST, '/properties/999999', 404);
    }

    public function testUpdateNonExistentProperty()
    {
        $data = [
            'name' => 'Non Existent Property',
            'location' => 'Non Existent Location',
            'price' => 300000,
        ];

        $this->assertUpdate(AuthMethod::MANAGER, '/properties/999999', $data, 404);
    }

    public function testDestroyNonExistentProperty()
    {
        $this->assertDelete(AuthMethod::ADMIN, '/properties/999999', 404);
    }
}
