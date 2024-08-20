<?php

namespace Tests\Feature\City;

use App\Models\City;
use App\Models\State;
use Tests\Constants\AuthMethod;
use Tests\MainTenantApiTest;

class CityTest extends MainTenantApiTest
{
    protected $tenancy = true;

    public function testIndex()
    {
        City::factory(3)->create();

        $this->setAuthNewGuest();
        $response = $this->api('GET', '/cities');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'state' => [
                    'id',
                    'name',
                ]],
            ],
        ]);
        $response->assertJsonCount(3, 'data');
    }

    public function testStore()
    {
        $data = [
            'name' => 'Test City',
            'state_id' => State::factory()->create()->id,
        ];

        $response = $this->assertCreation(AuthMethod::ADMIN, '/cities', $data, 201);
        $response->assertJsonStructure(['data' => ['id', 'name', 'state' => ['id', 'name']]]);
        $response->assertJson(['data' => [
            'name' => $data['name'],
            'state' => [
                'id' => $data['state_id'],
            ],
        ]]);

        $this->assertDatabaseHas('cities', $data);

        $this->assertCreation(AuthMethod::MANAGER, '/cities', [
            'name' => 'Test City 2',
            'state_id' => State::factory()->create()->id,
        ], 201);

        $this->assertCreation(AuthMethod::ASSISTANT, '/cities', [
            'name' => 'Test City 3',
            'state_id' => State::factory()->create()->id,
        ], 403);

        $this->assertCreation(AuthMethod::GUEST, '/cities', [
            'name' => 'Test City 4',
            'state_id' => State::factory()->create()->id,
        ], 403);
    }

    public function testShow()
    {
        $city = City::factory()->create();

        $response = $this->assertRead(AuthMethod::GUEST, "/cities/{$city->id}", 200);
        $response->assertJsonStructure(['data' => ['id', 'name', 'state' => ['id', 'name']]]);
        $response->assertJson([
            'data' => [
                'id' => $city->id,
                'name' => $city->name,
                'state' => [
                    'id' => $city->state->id,
                    'name' => $city->state->name,
                ],
            ],
        ]);
    }

    public function testUpdate()
    {
        $city = City::factory()->create();
        $data = [
            'name' => 'Updated City',
        ];

        $response = $this->assertUpdate(AuthMethod::ADMIN, "/cities/{$city->id}", $data, 200);
        $response->assertJsonStructure(['data' => ['id', 'name', 'state' => ['id', 'name']]]);
        $response->assertJson([
            'data' => [
                'id' => $city->id,
                'name' => $data['name'],
                'state' => [
                    'id' => $city->state->id,
                    'name' => $city->state->name,
                ],
            ],
        ]);
        $this->assertDatabaseHas('cities', $data);

        $this->assertUpdate(AuthMethod::MANAGER, "/cities/{$city->id}", $data, 200);
        $this->assertUpdate(AuthMethod::ASSISTANT, "/cities/{$city->id}", $data, 200);
        $this->assertUpdate(AuthMethod::GUEST, "/cities/{$city->id}", $data, 403);
    }

    public function testDestroy()
    {
        $city = City::factory()->create();
        $this->assertDelete(AuthMethod::ADMIN, "/cities/{$city->id}", 204);
        $this->assertDatabaseMissing('cities', ['id' => $city->id]);

        $city = City::factory()->create();
        $this->assertDelete(AuthMethod::MANAGER, "/cities/{$city->id}", 403);
        $this->assertDelete(AuthMethod::ASSISTANT, "/cities/{$city->id}", 403);
        $this->assertDelete(AuthMethod::GUEST, "/cities/{$city->id}", 403);

        $this->assertDatabaseHas('cities', ['id' => $city->id]);
    }

    public function testShowNonExistentCity()
    {
        $this->assertRead(AuthMethod::GUEST, '/cities/999999', 404);
    }

    public function testUpdateNonExistentCity()
    {
        $data = [
            'name' => 'Non Existent City',
            'state_id' => State::factory()->create()->id,
        ];

        $this->assertUpdate(AuthMethod::MANAGER, '/cities/999999', $data, 404);
    }

    public function testDestroyNonExistentCity()
    {
        $this->assertDelete(AuthMethod::ADMIN, '/cities/999999', 404);
    }
}
