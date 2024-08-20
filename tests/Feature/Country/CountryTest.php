<?php

namespace Tests\Feature\Country;

use App\Models\Country;
use Tests\Constants\AuthMethod;
use Tests\MainTenantApiTest;

class CountryTest extends MainTenantApiTest
{
    protected $tenancy = true;

    public function testIndex()
    {
        Country::factory(3)->create();

        $this->setAuthNewGuest();
        $response = $this->api('GET', '/countries');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'code'],
            ],
        ]);
        $response->assertJsonCount(3, 'data');
    }

    public function testStore()
    {
        $data = [
            'name' => 'Test Country',
            'code' => 'TC',
        ];

        $response = $this->assertCreation(AuthMethod::ADMIN, '/countries', $data, 201);
        $response->assertJsonStructure(['data' => ['id', 'name', 'code']]);
        $response->assertJson(['data' => $data]);
        $this->assertDatabaseHas('countries', $data);

        $this->assertCreation(AuthMethod::MANAGER, '/countries', [
            'name' => 'Test Country 2',
            'code' => 'TC2',
        ], 201);

        $this->assertCreation(AuthMethod::ASSISTANT, '/countries', [
            'name' => 'Test Country 3',
            'code' => 'TC3',
        ], 403);

        $this->assertCreation(AuthMethod::GUEST, '/countries', [
            'name' => 'Test Country 4',
            'code' => 'TC4',
        ], 403);
    }

    public function testShow()
    {
        $country = Country::factory()->create();

        $response = $this->assertRead(AuthMethod::GUEST, "/countries/{$country->id}", 200);
        $response->assertJsonStructure(['data' => ['id', 'name', 'code']]);
        $response->assertJson([
            'data' => [
                'id' => $country->id,
                'name' => $country->name,
                'code' => $country->code,
            ],
        ]);
    }

    public function testUpdate()
    {
        $country = Country::factory()->create();
        $data = [
            'name' => 'Updated Country',
        ];

        $response = $this->assertUpdate(AuthMethod::ADMIN, "/countries/{$country->id}", $data, 200);
        $response->assertJsonStructure(['data' => ['id', 'name', 'code']]);
        $response->assertJson([
            'data' => [
                'id' => $country->id,
                'name' => $data['name'],
            ],
        ]);
        $this->assertDatabaseHas('countries', $data);

        $this->assertUpdate(AuthMethod::MANAGER, "/countries/{$country->id}", $data, 200);
        $this->assertUpdate(AuthMethod::ASSISTANT, "/countries/{$country->id}", $data, 200);
        $this->assertUpdate(AuthMethod::GUEST, "/countries/{$country->id}", $data, 403);
    }

    public function testDestroy()
    {
        $country = Country::factory()->create();

        $this->assertDelete(AuthMethod::ADMIN, "/countries/{$country->id}", 204);
        $this->assertDatabaseMissing('countries', ['id' => $country->id]);

        $country = Country::factory()->create();
        $this->assertDelete(AuthMethod::MANAGER, "/countries/{$country->id}", 403);
        $this->assertDelete(AuthMethod::ASSISTANT, "/countries/{$country->id}", 403);
        $this->assertDelete(AuthMethod::GUEST, "/countries/{$country->id}", 403);

        $this->assertDatabaseHas('countries', ['id' => $country->id]);
    }

    public function testShowNonExistentCountry()
    {
        $this->assertRead(AuthMethod::GUEST, '/countries/999999', 404);
    }

    public function testUpdateNonExistentCountry()
    {
        $data = [
            'name' => 'Non Existent Country',
            'code' => 'NEC',
        ];

        $this->assertUpdate(AuthMethod::MANAGER, '/countries/999999', $data, 404);
    }

    public function testDestroyNonExistentCountry()
    {
        $this->assertDelete(AuthMethod::ADMIN, '/countries/999999', 404);
    }
}
