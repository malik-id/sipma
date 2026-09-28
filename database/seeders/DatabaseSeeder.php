<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles
        $adminPermissions = array_map(fn (Permission $p) => $p->value, Permission::cases());

        Role::updateOrCreate(
            ['name' => 'super_admin'],
            ['permissions' => $adminPermissions]
        );

        Role::updateOrCreate(
            ['name' => 'admin'],
            ['permissions' => $adminPermissions]
        );

        // 2. Akun Super Admin default
        User::updateOrCreate(
            ['email' => 'admin@himakom.ac.id'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
