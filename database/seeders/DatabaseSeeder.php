<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('local', 'development', 'testing')) {
            $this->call(DevelopmentSeeder::class);
        } elseif (app()->environment('production')) {
            $this->call(ProductionSeeder::class);
        }
    }
}
