<?php

namespace Tests\Feature\Agent;

use App\Models\Agent;
use Tests\Constants\AuthMethod;
use Tests\MainTenantApiTest;
use Tests\Traits\RefreshTenantDatabase;

class AgentTest extends MainTenantApiTest
{
    // use RefreshTenantDatabase;

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
        Agent::factory(3)->create();

        $this->setAuthNewGuest();
        $response = $this->api('GET', '/agents');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'phone', 'email'],
            ],
        ]);
        $response->assertJsonCount(3, 'data');
    }

    public function testStore()
    {
        $data = [
            'name' => 'Test Agent 1',
            'phone' => '1987654321',
            'email' => 'agent1@test.com',
        ];

        // $response = $this->assertCreation(AuthMethod::ADMIN, '/agents', $data, 201);
        // $response->assertJsonStructure(['data' => ['id', 'name', 'phone', 'email']]);
        // $response->assertJson(['data' => $data]);
        // $this->assertDatabaseHas('agents', $data);

        $this->assertCreation(AuthMethod::MANAGER, '/agents', [
            'name' => 'Test Agent 2',
            'phone' => '2987654321',
            'email' => 'agent2@test.com',
        ], 201);

        $this->assertCreation(AuthMethod::ASSISTANT, '/agents', [
            'name' => 'Test Agent 3',
            'phone' => '3987654321',
            'email' => 'agent3@test.com',
        ], 403);

        $this->assertCreation(AuthMethod::GUEST, '/agents', [
            'name' => 'Test Agent 4',
            'phone' => '4987654321',
            'email' => 'agent4@test.com',
        ], 403);
    }

    public function testShow()
    {
        $agent = Agent::factory()->create();

        $response = $this->assertRead(AuthMethod::GUEST, "/agents/{$agent->id}", 200);
        $response->assertJsonStructure(['data' => ['id', 'name', 'phone', 'email']]);
        $response->assertJson([
            'data' => [
                'id' => $agent->id,
                'name' => $agent->name,
                'phone' => $agent->phone,
                'email' => $agent->email,
            ],
        ]);
    }

    public function testUpdate()
    {
        $agent = Agent::factory()->create();

        $data = [
            'name' => 'Updated Agent',
            'phone' => '987654321',
            'email' => 'agentb@example.com',
        ];

        $response = $this->assertUpdate(AuthMethod::ADMIN, "/agents/{$agent->id}", $data, 200);
        $response->assertJsonStructure(['data' => ['id', 'name', 'phone', 'email']]);

        $response->assertJson([
            'data' => [
                'id' => $agent->id,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ],
        ]);
        $this->assertDatabaseHas('agents', $data);

        $this->assertUpdate(AuthMethod::MANAGER, "/agents/{$agent->id}", $data, 200);
        $this->assertUpdate(AuthMethod::ASSISTANT, "/agents/{$agent->id}", $data, 200);
        $this->assertUpdate(AuthMethod::GUEST, "/agents/{$agent->id}", $data, 403);
    }

    public function testDestroy()
    {
        $agent = Agent::factory()->create();
        $this->assertDelete(AuthMethod::ADMIN, "/agents/{$agent->id}", 204);
        $this->assertDatabaseMissing('agents', ['id' => $agent->id]);

        $agent = Agent::factory()->create();
        $this->assertDelete(AuthMethod::MANAGER, "/agents/{$agent->id}", 403);
        $this->assertDelete(AuthMethod::ASSISTANT, "/agents/{$agent->id}", 403);
        $this->assertDelete(AuthMethod::GUEST, "/agents/{$agent->id}", 403);
        $this->assertDatabaseHas('agents', ['id' => $agent->id]);
    }

    public function testShowNonExistentAgent()
    {
        $this->assertRead(AuthMethod::GUEST, '/agents/999999', 404);
    }

    public function testUpdateNonExistentAgent()
    {
        $data = [
            'name' => 'Non Existent Agent',
            'phone' => '000000000',
            'email' => 'nonexistent@example.com',
        ];

        $this->assertUpdate(AuthMethod::MANAGER, '/agents/999999', $data, 404);
    }

    public function testDestroyNonExistentAgent()
    {
        $this->assertDelete(AuthMethod::ADMIN, '/agents/999999', 404);
    }
}
