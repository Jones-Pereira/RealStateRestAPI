<?php

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $tenancy = false;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->tenancy) {
            $this->initializeTenancy();
        }
    }

    public function initializeTenancy()
    {
        $tenantId = 'tt';

        // Verifica se o tenant já existe
        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            // Cria um novo tenant se não existir
            $tenant = Tenant::create(['id' => $tenantId]);
        }

        tenancy()->initialize($tenant);

        // Executa as migrações específicas dos tenants
        $this->artisan('tenants:migrate', ['--tenants' => [$tenant->id]]);
    }
}
