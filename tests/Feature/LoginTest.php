<?php

namespace Tests\Feature;

use Tests\MainTenantApiTest;

class LoginTest extends MainTenantApiTest
{
    protected $tenancy = true;

    public function test_login(): void
    {
        $this->api('POST', '/register', [
            'name' => 'Test User',
            'email' => 'taests@test.com',
            'password' => 'password',
            'c_password' => 'password',
        ])->assertStatus(200);

        $this->api('POST', '/login', [
            'email' => 'taests@test.com',
            'password' => 'password',
        ])->assertStatus(200);
    }

    public function test_login_with_invalid_credentials(): void
    {
        $this->api('POST', '/register', [
            'name' => 'Test User',
            'email' => 'taests@test.com',
            'password' => 'password',
            'c_password' => 'password',
        ])->assertStatus(200);

        $this->api('POST', '/api/login', [
            'email' => 'taests@test.com',
            'password' => '123456',
        ])->assertStatus(404);
    }

    public function test_login_with_invalid_email(): void
    {
        $this->api('POST', '/api/register', [
            'name' => 'Test User',
            'email' => 'asd@asdasd',
            'password' => 'password',
        ])->assertStatus(404);
    }
}
