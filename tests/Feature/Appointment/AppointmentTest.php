<?php

namespace Tests\Feature\Appointment;

use App\Models\Agent;
use App\Models\Appointment;
use App\Models\Property;
use App\Models\User;
use Tests\Constants\AuthMethod;
use Tests\MainTenantApiTest;

class AppointmentTest extends MainTenantApiTest
{
    protected $tenancy = true;

    public function testIndex()
    {
        Appointment::factory(3)->create();

        $this->setAuthNewGuest();
        $response = $this->api('GET', '/appointments');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'property_id',
                    'agent_id',
                    'date',
                    'time',
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
            'user_id' => User::factory()->create()->id,
            'property_id' => Property::factory()->create()->id,
            'agent_id' => Agent::factory()->create()->id,
            'date' => '2023-10-01',
            'time' => '10:00:00',
            'status' => 'pending',
        ];

        $response = $this->assertCreation(AuthMethod::ADMIN, '/appointments', $data, 201);
        $response->assertJsonStructure(['data' => [
            'user_id',
            'property_id',
            'agent_id',
            'date',
            'time',
            'status',
        ]]);
        $response->assertJson(['data' => $data]);
        $this->assertDatabaseHas('appointments', $data);

        $this->assertCreation(AuthMethod::MANAGER, '/appointments', [
            'user_id' => User::factory()->create()->id,
            'property_id' => Property::factory()->create()->id,
            'agent_id' => Agent::factory()->create()->id,
            'date' => '2023-10-02',
            'time' => '11:00:00',
            'status' => 'pending',
        ], 201);

        $this->assertCreation(AuthMethod::ASSISTANT, '/appointments', [
            'user_id' => User::factory()->create()->id,
            'property_id' => Property::factory()->create()->id,
            'agent_id' => Agent::factory()->create()->id,
            'date' => '2023-10-03',
            'time' => '12:00:00',
            'status' => 'pending',
        ], 403);

        $this->assertCreation(AuthMethod::GUEST, '/appointments', [
            'user_id' => User::factory()->create()->id,
            'property_id' => Property::factory()->create()->id,
            'agent_id' => Agent::factory()->create()->id,
            'date' => '2023-10-04',
            'time' => '13:00:00',
            'status' => 'pending',
        ], 403);
    }

    public function testShow()
    {
        $appointment = Appointment::factory()->create();

        $response = $this->assertRead(AuthMethod::GUEST, "/appointments/{$appointment->id}", 200);
        $response->assertJsonStructure(['data' => [
            'user_id',
            'property_id',
            'agent_id',
            'date',
            'time',
            'status',
        ]]);

        $response->assertJson([
            'data' => [
                'user_id' => $appointment->user_id,
                'property_id' => $appointment->property_id,
                'agent_id' => $appointment->agent_id,
                'date' => $appointment->date,
                'time' => $appointment->time,
                'status' => $appointment->status,
            ],
        ]);
    }

    public function testUpdate()
    {
        $appointment = Appointment::factory()->create();
        $data = [
            'date' => '2023-10-02',
            'time' => '11:00:00',
            'status' => 'approved',
        ];

        $response = $this->assertUpdate(AuthMethod::ADMIN, "/appointments/{$appointment->id}", $data, 200);
        $response->assertJsonStructure(['data' => [
            'user_id',
            'property_id',
            'agent_id',
            'date',
            'time',
            'status',
        ]]);
        $response->assertJson([
            'data' => [
                'id' => $appointment->id,
                'user_id' => $appointment->user_id,
                'property_id' => $appointment->property_id,
                'agent_id' => $appointment->agent_id,
                'date' => $data['date'],
                'time' => $data['time'],
                'status' => $data['status'],
            ],
        ]);
        $this->assertDatabaseHas('appointments', $data);

        $this->assertUpdate(AuthMethod::MANAGER, "/appointments/{$appointment->id}", $data, 200);
        $this->assertUpdate(AuthMethod::ASSISTANT, "/appointments/{$appointment->id}", $data, 200);
        $this->assertUpdate(AuthMethod::GUEST, "/appointments/{$appointment->id}", $data, 403);
    }

    public function testDestroy()
    {
        $appointment = Appointment::factory()->create();
        $this->assertDelete(AuthMethod::ADMIN, "/appointments/{$appointment->id}", 204);
        $this->assertDatabaseMissing('appointments', ['id' => $appointment->id]);

        $appointment = Appointment::factory()->create();
        $this->assertDelete(AuthMethod::MANAGER, "/appointments/{$appointment->id}", 403);
        $this->assertDelete(AuthMethod::ASSISTANT, "/appointments/{$appointment->id}", 403);
        $this->assertDelete(AuthMethod::GUEST, "/appointments/{$appointment->id}", 403);
        $this->assertDatabaseHas('appointments', ['id' => $appointment->id]);
    }

    public function testShowNonExistentAppointment()
    {
        $this->assertRead(AuthMethod::GUEST, '/appointments/999999', 404);
    }

    public function testUpdateNonExistentAppointment()
    {
        $data = [
            'date' => '2023-10-02',
            'time' => '11:00:00',
            'status' => 'approved',
        ];

        $this->assertUpdate(AuthMethod::MANAGER, '/appointments/999999', $data, 404);
    }

    public function testDestroyNonExistentAppointment()
    {
        $this->assertDelete(AuthMethod::ADMIN, '/appointments/999999', 404);
    }
}
