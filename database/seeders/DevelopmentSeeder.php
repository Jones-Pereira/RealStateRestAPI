<?php

namespace Database\Seeders;

use Database\Seeders\Development\AgentSeeder;
use Database\Seeders\Development\AppointmentSeeder;
use Database\Seeders\Development\CitySeeder;
use Database\Seeders\Development\CountrySeeder;
use Database\Seeders\Development\ImageSeeder;
use Database\Seeders\Development\PropertySeeder;
use Database\Seeders\Development\RolePermissionSeeder;
use Database\Seeders\Development\StateSeeder;
use Database\Seeders\Development\UserSeeder;
use Illuminate\Database\Seeder;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            CountrySeeder::class,
            CitySeeder::class,
            StateSeeder::class,
            AgentSeeder::class,
            AppointmentSeeder::class,
            ImageSeeder::class,
            PropertySeeder::class,
        ]);
    }
}
