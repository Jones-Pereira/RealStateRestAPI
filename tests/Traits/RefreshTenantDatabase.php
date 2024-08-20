<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait RefreshTenantDatabase
{
    protected function refreshTenantDatabase()
    {
        $this->truncateAllTables();

        // Executa os seeders para o banco de dados do inquilino
        Artisan::call('tenants:seed', [
            '--class' => 'Database\Seeders\Development\RolePermissionSeeder',
            '--tenants' => [$this->tenant->id],
        ]);
    }

    protected function truncateAllTables()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $databaseName = 'tenant'.$this->tenantId;

        // Gera os comandos TRUNCATE TABLE apenas para as tabelas que possuem dados
        $truncateCommands = DB::select("
            SELECT CONCAT('TRUNCATE TABLE ', table_name, ';') AS truncate_command
            FROM information_schema.tables
            WHERE table_schema = ?
              AND table_type = 'BASE TABLE'
              AND table_rows > 0;
        ", [$databaseName]);

        // Executa cada comando TRUNCATE TABLE
        foreach ($truncateCommands as $command) {
            Log::info('Executing: '.$command->truncate_command);
            DB::statement($command->truncate_command);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshTenantDatabase();
    }

    protected function tearDown(): void
    {
        $this->refreshTenantDatabase();
        parent::tearDown();
    }
}
