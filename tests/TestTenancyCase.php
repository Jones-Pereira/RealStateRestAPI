<?php

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Domain;

abstract class TestTenancyCase extends BaseTestCase
{
    use RefreshDatabase;

    protected $tenantId = 'es';

    protected $tenantDomain = null;

    protected $tenant = null;

    protected $tenancy = false;

    protected function setUp(): void
    {
        parent::setUp();
        if ($this->tenancy) {
            $this->initializeTenancy();
        }
    }

    protected function tearDown(): void
    {
        $this->truncateTables();
        parent::tearDown();
    }

    public function initializeTenancy()
    {
        $this->tenant = Tenant::where('id', $this->tenantId)->first();

        $this->tenantDomain = $this->tenantId.'.localhost';

        if (! $this->tenant) {
            $databaseName = 'tenant'.$this->tenantId;
            DB::statement("DROP DATABASE IF EXISTS `$databaseName`");

            $this->tenant = Tenant::create([
                'id' => $this->tenantId,
            ]);

            Domain::create([
                'domain' => $this->tenantDomain,
                'tenant_id' => $this->tenant->id,
            ]);

            $this->artisan('tenants:seed', [
                '--class' => 'Database\Seeders\Development\RolePermissionSeeder',
                '--tenants' => [$this->tenant->id],
            ]);
        }

        tenancy()->initialize($this->tenant);

        $this->artisan('tenants:seed', [
            '--class' => 'Database\Seeders\Development\RolePermissionSeeder',
            '--tenants' => [$this->tenant->id],
        ]);
    }

    protected static function truncateTables()
    {
        DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=0;');
        $tables = DB::connection('tenant')->select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            if (DB::connection('tenant')->table($tableName)->count()) {
                DB::connection('tenant')->table($tableName)->truncate();
            }
        }
        DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    // public static function setUpBeforeClass(): void
    // {
    //     parent::setUpBeforeClass();
    //     dump('--------------setUpBeforeClass------------------------------');
    // }

    // public static function tearDownAfterClass(): void
    // {
    //     dump('--------------tearDownAfterClass------------------------------');
    //     parent::tearDownAfterClass();
    // }
}
