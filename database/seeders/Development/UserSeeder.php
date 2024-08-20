<?php

namespace Database\Seeders\Development;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = Role::whereName('admin')->value('id');
        $managerId = Role::whereName('manager')->value('id');
        $assistantId = Role::whereName('assistant')->value('id');
        $guestId = Role::whereName('guest')->value('id');

        $tenantName = tenant('id');

        // Admin User
        User::firstOrCreate([
            'name' => "$tenantName Admin User",
            'email' => 'admin@dev.com',
            'password' => bcrypt('password'),
        ])->roles()->attach($adminId);

        // Manager User
        User::firstOrCreate([
            'name' => "$tenantName Manager User",
            'email' => 'manager@dev.com',
            'password' => bcrypt('password'),
        ])->roles()->attach($managerId);

        // Assistant User
        User::firstOrCreate([
            'name' => "$tenantName Assistant User",
            'email' => 'editor@dev.com',
            'password' => bcrypt('password'),
        ])->roles()->attach($assistantId);

        // Guest User
        $guest = User::firstOrCreate([
            'name' => "$tenantName Guest User",
            'email' => 'guest@dev.com',
            'password' => bcrypt('password'),
        ])->roles()->attach($guestId);
    }
}
