<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'read users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        Permission::create(['name' => 'create contract types']);
        Permission::create(['name' => 'read contract types']);
        Permission::create(['name' => 'edit contract types']);
        Permission::create(['name' => 'delete contract types']);

        Permission::create(['name' => 'create departments']);
        Permission::create(['name' => 'read departments']);
        Permission::create(['name' => 'edit departments']);
        Permission::create(['name' => 'delete departments']);

        Permission::create(['name' => 'create educations']);
        Permission::create(['name' => 'read educations']);
        Permission::create(['name' => 'edit educations']);
        Permission::create(['name' => 'delete educations']);

        Permission::create(['name' => 'create identity types']);
        Permission::create(['name' => 'read identity types']);
        Permission::create(['name' => 'edit identity types']);
        Permission::create(['name' => 'delete identity types']);

        Permission::create(['name' => 'create jobs']);
        Permission::create(['name' => 'read jobs']);
        Permission::create(['name' => 'edit jobs']);
        Permission::create(['name' => 'delete jobs']);

        Permission::create(['name' => 'create legality types']);
        Permission::create(['name' => 'read legality types']);
        Permission::create(['name' => 'edit legality types']);
        Permission::create(['name' => 'delete legality types']);

        Permission::create(['name' => 'create marital statuses']);
        Permission::create(['name' => 'read marital statuses']);
        Permission::create(['name' => 'edit marital statuses']);
        Permission::create(['name' => 'delete marital statuses']);

        Permission::create(['name' => 'create payroll components']);
        Permission::create(['name' => 'read payroll components']);
        Permission::create(['name' => 'edit payroll components']);
        Permission::create(['name' => 'delete payroll components']);

        Permission::create(['name' => 'create positions']);
        Permission::create(['name' => 'read positions']);
        Permission::create(['name' => 'edit positions']);
        Permission::create(['name' => 'delete positions']);

        Permission::create(['name' => 'create relationships']);
        Permission::create(['name' => 'read relationships']);
        Permission::create(['name' => 'edit relationships']);
        Permission::create(['name' => 'delete relationships']);

        Permission::create(['name' => 'create religions']);
        Permission::create(['name' => 'read religions']);
        Permission::create(['name' => 'edit religions']);
        Permission::create(['name' => 'delete religions']);

        Permission::create(['name' => 'create sexs']);
        Permission::create(['name' => 'read sexs']);
        Permission::create(['name' => 'edit sexs']);
        Permission::create(['name' => 'delete sexs']);

        Permission::create(['name' => 'create skill types']);
        Permission::create(['name' => 'read skill types']);
        Permission::create(['name' => 'edit skill types']);
        Permission::create(['name' => 'delete skill types']);

        Permission::create(['name' => 'create status employements']);
        Permission::create(['name' => 'read status employements']);
        Permission::create(['name' => 'edit status employements']);
        Permission::create(['name' => 'delete status employements']);

        Permission::create(['name' => 'create taxs']);
        Permission::create(['name' => 'read taxs']);
        Permission::create(['name' => 'edit taxs']);
        Permission::create(['name' => 'delete taxs']);

        Permission::create(['name' => 'create units']);
        Permission::create(['name' => 'read units']);
        Permission::create(['name' => 'edit units']);
        Permission::create(['name' => 'delete units']);

        Permission::create(['name' => 'create overtime statuses']);
        Permission::create(['name' => 'read overtime statuses']);
        Permission::create(['name' => 'edit overtime statuses']);
        Permission::create(['name' => 'delete overtime statuses']);

        Permission::create(['name' => 'create shift groups']);
        Permission::create(['name' => 'read shift groups']);
        Permission::create(['name' => 'edit shift groups']);
        Permission::create(['name' => 'delete shift groups']);
    }
}
