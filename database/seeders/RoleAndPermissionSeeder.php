<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

final class RoleAndPermissionSeeder extends Seeder
{
    private array $roles = [
        'admin',
        'hrd',
        'employee',
        'manager'
    ];

    public function run(): void
    {
        Role::query()->truncate();

        foreach ($this->roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'api']);
        }
    }
}
