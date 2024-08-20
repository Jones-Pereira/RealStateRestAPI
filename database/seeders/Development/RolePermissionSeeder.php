<?php

namespace Database\Seeders\Development;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Permissões
        Permission::updateOrCreate(['name' => 'create'], ['guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'read'], ['guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'update'], ['guard_name' => 'web']);
        Permission::updateOrCreate(['name' => 'delete'], ['guard_name' => 'web']);

        // Roles
        $admin = Role::updateOrCreate(['name' => 'admin'], ['guard_name' => 'web']);
        $manager = Role::updateOrCreate(['name' => 'manager'], ['guard_name' => 'web']);
        $assistant = Role::updateOrCreate(['name' => 'assistant'], ['guard_name' => 'web']);
        $guest = Role::updateOrCreate(['name' => 'guest'], ['guard_name' => 'web']);

        // Permissões para cada role
        $permissions = Permission::pluck('id', 'name');

        DB::table('role_has_permissions')->updateOrInsert(['role_id' => $admin->id, 'permission_id' => $permissions['create']]);
        DB::table('role_has_permissions')->updateOrInsert(['role_id' => $admin->id, 'permission_id' => $permissions['read']]);
        DB::table('role_has_permissions')->updateOrInsert(['role_id' => $admin->id, 'permission_id' => $permissions['update']]);
        DB::table('role_has_permissions')->updateOrInsert(['role_id' => $admin->id, 'permission_id' => $permissions['delete']]);

        DB::table('role_has_permissions')->updateOrInsert(['role_id' => $manager->id, 'permission_id' => $permissions['create']]);
        DB::table('role_has_permissions')->updateOrInsert(['role_id' => $manager->id, 'permission_id' => $permissions['read']]);
        DB::table('role_has_permissions')->updateOrInsert(['role_id' => $manager->id, 'permission_id' => $permissions['update']]);

        DB::table('role_has_permissions')->updateOrInsert(['role_id' => $assistant->id, 'permission_id' => $permissions['read']]);
        DB::table('role_has_permissions')->updateOrInsert(['role_id' => $assistant->id, 'permission_id' => $permissions['update']]);

        DB::table('role_has_permissions')->updateOrInsert(['role_id' => $guest->id, 'permission_id' => $permissions['read']]);

        // Permission::insert(['name' => 'create', 'guard_name' => 'web']);
        // Permission::insert(['name' => 'read', 'guard_name' => 'web']);
        // Permission::insert(['name' => 'update', 'guard_name' => 'web']);
        // Permission::insert(['name' => 'delete', 'guard_name' => 'web']);

        // Role::insert(['name' => 'admin', 'guard_name' => 'web']);
        // Role::insert(['name' => 'manager', 'guard_name' => 'web']);
        // Role::insert(['name' => 'assistant', 'guard_name' => 'web']);
        // Role::insert(['name' => 'guest', 'guard_name' => 'web']);

        // $admin = Role::whereName('admin')->first();
        // $manager = Role::whereName('manager')->first();
        // $assistant = Role::whereName('assistant')->first();
        // $guest = Role::whereName('guest')->first();

        // DB::table('role_has_permissions')->insert(['role_id' => $admin->id, 'permission_id' => 1]);
        // DB::table('role_has_permissions')->insert(['role_id' => $admin->id, 'permission_id' => 2]);
        // DB::table('role_has_permissions')->insert(['role_id' => $admin->id, 'permission_id' => 3]);
        // DB::table('role_has_permissions')->insert(['role_id' => $admin->id, 'permission_id' => 4]);

        // DB::table('role_has_permissions')->insert(['role_id' => $manager->id, 'permission_id' => 1]);
        // DB::table('role_has_permissions')->insert(['role_id' => $manager->id, 'permission_id' => 2]);
        // DB::table('role_has_permissions')->insert(['role_id' => $manager->id, 'permission_id' => 3]);

        // DB::table('role_has_permissions')->insert(['role_id' => $assistant->id, 'permission_id' => 2]);
        // DB::table('role_has_permissions')->insert(['role_id' => $assistant->id, 'permission_id' => 3]);

        // DB::table('role_has_permissions')->insert(['role_id' => $guest->id, 'permission_id' => 2]);
    }
}
