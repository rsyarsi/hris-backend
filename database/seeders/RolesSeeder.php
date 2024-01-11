<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'ADMINISTRATOR']);
        Role::create(['name' => 'HRD']);
        Role::create(['name' => 'MANAGER']);
        Role::create(['name' => 'SUPERVISOR']);
        Role::create(['name' => 'EMPLOYEE']);
        Role::create(['name' => 'KABAG']);
    }
}
