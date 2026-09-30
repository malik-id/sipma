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

        // Supervisor / Dosen Pendamping permissions (view-only: monitor, results, audit)
        $supervisorPermissions = [
            Permission::Monitor->value,
            Permission::Results->value,
            Permission::Audit->value,
        ];

        Role::updateOrCreate(
            ['name' => 'dosen_pendamping'],
            ['permissions' => $supervisorPermissions]
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

        // 3. Akun Dosen Pendamping default
        User::updateOrCreate(
            ['email' => 'dosen@himakom.ac.id'],
            [
                'name' => 'Dosen Pendamping (Pengawas)',
                'password' => Hash::make('dosen123'),
                'role' => 'dosen_pendamping',
                'active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
