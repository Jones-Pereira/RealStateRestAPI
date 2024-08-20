<?php

namespace Tests;

use App\Constants\UserRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;

abstract class MainTenantApiTest extends TestTenancyCase
{
    use RefreshDatabase;

    protected $tenancy = false;

    protected $baseUrl = null;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseUrl = sprintf('http://%s/api', $this->tenantDomain);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Faz login e armazena o token de autenticação.
     *
     * @return void
     */
    protected function login(array $credentials = ['email' => 'test@example.com', 'password' => 'password'])
    {
        $user = User::factory()->create([
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password']),
        ]);

        $response = $this->postJson("{$this->baseUrl}/login", $credentials)->assertStatus(200)->json('data');

        $this->token = $response['token'];
    }

    /**
     * Retorna os headers de autenticação.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return [
            'Authorization' => "Bearer {$this->token}",
            'Accept' => 'application/json',
        ];
    }

    /**
     * Faz uma requisição para a API autenticada.
     *
     * @return \Illuminate\Testing\TestResponse
     */
    protected function api(string $method, string $uri, array $data = [])
    {
        // $headers = $this->getHeaders();
        $headers = [];

        switch (strtoupper($method)) {
            case 'GET':
                return $this->getJson("{$this->baseUrl}{$uri}", $headers);
            case 'POST':
                return $this->postJson("{$this->baseUrl}{$uri}", $data, $headers);
            case 'PUT':
                return $this->putJson("{$this->baseUrl}{$uri}", $data, $headers);
            case 'DELETE':
                return $this->deleteJson("{$this->baseUrl}{$uri}", $data, $headers);
            default:
                throw new \InvalidArgumentException("Unsupported method: {$method}");
        }
    }

    protected function setAuthNewAdmin()
    {
        $user = $this->createNewUser(UserRoles::ADMIN);
    }

    protected function setAuthNewManager()
    {
        return $this->createNewUser(UserRoles::MANAGER);
    }

    protected function setAuthNewAssistant()
    {
        return $this->createNewUser(UserRoles::ASSISTANT);
    }

    protected function setAuthNewGuest()
    {
        return $this->createNewUser(UserRoles::GUEST);
    }

    private function createNewUser($role = null): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);
        // Sanctum::actingAs($user);
        $this->actingAs($user);

        return $user;
    }

    /**
     * Método auxiliar para configurar a autenticação e executar a requisição.
     *
     * @return void
     */
    protected function assertCreation(string $authMethod, string $url, array $data, int $expectedStatus): TestResponse
    {
        $this->$authMethod();
        try {
            $request = $this->api('POST', $url, $data)->assertStatus($expectedStatus);
        } catch (\Exception $e) {
            $this->api('POST', $url, $data)->dump();
        }

        return $request;
    }

    /**
     * Método auxiliar para configurar a autenticação e executar a requisição de leitura.
     *
     * @param  array  $data
     */
    protected function assertRead(string $authMethod, string $url, int $expectedStatus): TestResponse
    {
        $this->$authMethod();
        try {
            $request = $this->api('GET', $url)->assertStatus($expectedStatus);
        } catch (\Exception $e) {
            $this->api('GET', $url)->dump();
        }

        return $request;
    }

    /**
     * Método auxiliar para configurar a autenticação e executar a requisição de atualização.
     */
    protected function assertUpdate(string $authMethod, string $url, array $data, int $expectedStatus): TestResponse
    {
        $this->$authMethod();
        try {
            $request = $this->api('PUT', $url, $data)->assertStatus($expectedStatus);
        } catch (\Exception $e) {
            $this->api('PUT', $url, $data)->dump();
        }

        return $request;
    }

    /**
     * Método auxiliar para configurar a autenticação e executar a requisição de exclusão.
     *
     * @param  array  $data
     */
    protected function assertDelete(string $authMethod, string $url, int $expectedStatus): TestResponse
    {
        $this->$authMethod();
        try {
            $request = $this->api('DELETE', $url)->assertStatus($expectedStatus);
        } catch (\Exception $e) {
            $this->api('DELETE', $url)->dump();
        }

        return $request;
    }
}
