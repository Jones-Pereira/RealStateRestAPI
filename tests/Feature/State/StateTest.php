<?php

namespace Tests\Feature\State;

use App\Models\Country;
use App\Models\State;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Constants\AuthMethod;
use Tests\MainTenantApiTest;
use Tests\Traits\RefreshTenantDatabase;

class StateTest extends MainTenantApiTest
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
        State::factory(3)->create();

        $this->setAuthNewGuest();
        $response = $this->api('GET', '/states');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'country' => ['id', 'name'], 'created_at', 'updated_at'],
            ],
        ]);
        $response->assertJsonCount(3, 'data');
    }

    public function testStore()
    {
        $data = [
            'name' => 'Test State',
            'country_id' => Country::factory()->create()->id,
        ];

        $response = $this->assertCreation(AuthMethod::ADMIN, '/states', $data, 201);
        $response->assertJsonStructure(['data' => ['id', 'name', 'country' => ['id', 'name'], 'created_at', 'updated_at']]);
        $response->assertJson(['data' => [
            'name' => $data['name'],
            'country' => ['id' => $data['country_id']],
        ]]);
        $this->assertDatabaseHas('states', $data);

        $this->assertCreation(AuthMethod::MANAGER, '/states', [
            'name' => 'Test State 2',
            'country_id' => Country::factory()->create()->id,
        ], 201);

        $this->assertCreation(AuthMethod::ASSISTANT, '/states', [
            'name' => 'Test State 3',
            'country_id' => Country::factory()->create()->id,
        ], 403);

        $this->assertCreation(AuthMethod::GUEST, '/states', [
            'name' => 'Test State 4',
            'country_id' => Country::factory()->create()->id,
        ], 403);
    }

    public function testShow()
    {
        $state = State::factory()->create();

        $response = $this->assertRead(AuthMethod::GUEST, "/states/{$state->id}", 200);
        $response->assertJsonStructure(['data' => ['id', 'name', 'country' => ['id', 'name'], 'created_at', 'updated_at']]);
        $response->assertJson([
            'data' => [
                'id' => $state->id,
                'name' => $state->name,
                'country' => ['id' => $state->country_id],
            ],
        ]);
    }

    public function testUpdate()
    {
        $state = State::factory()->create();
        $data = [
            'name' => 'Updated State',
        ];

        $response = $this->assertUpdate(AuthMethod::ADMIN, "/states/{$state->id}", $data, 200);
        $response->assertJsonStructure(['data' => ['id', 'name', 'country' => ['id', 'name'], 'created_at', 'updated_at']]);
        $response->assertJson([
            'data' => [
                'id' => $state->id,
                'name' => $data['name'],
                'country' => ['id' => $state->country_id],
            ],
        ]);
        $this->assertDatabaseHas('states', $data);

        $this->assertUpdate(AuthMethod::MANAGER, "/states/{$state->id}", $data, 200);
        $this->assertUpdate(AuthMethod::ASSISTANT, "/states/{$state->id}", $data, 200);
        $this->assertUpdate(AuthMethod::GUEST, "/states/{$state->id}", $data, 403);
    }

    public function testDestroy()
    {
        $state = State::factory()->create();

        $this->assertDelete(AuthMethod::ADMIN, "/states/{$state->id}", 204);
        $this->assertDatabaseMissing('states', ['id' => $state->id]);

        $state = State::factory()->create();
        $this->assertDelete(AuthMethod::MANAGER, "/states/{$state->id}", 403);
        $this->assertDelete(AuthMethod::ASSISTANT, "/states/{$state->id}", 403);
        $this->assertDelete(AuthMethod::GUEST, "/states/{$state->id}", 403);

        $this->assertDatabaseHas('states', ['id' => $state->id]);
    }

    public function testShowNonExistentState()
    {
        $this->assertRead(AuthMethod::GUEST, '/states/999999', 404);
    }

    public function testUpdateNonExistentState()
    {
        $data = [
            'name' => 'Non Existent State',
            'code' => 'NES',
        ];

        $this->assertUpdate(AuthMethod::MANAGER, '/states/999999', $data, 404);
    }

    public function testDestroyNonExistentState()
    {
        $this->assertDelete(AuthMethod::ADMIN, '/states/999999', 404);
    }
}
