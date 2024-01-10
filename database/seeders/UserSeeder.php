<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'ADMINISTRATOR',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'username' => 'ADMIN',
            'administrator' => 1,
            'hrd' => 0,
            'manager' => 0,
            'supervisor' => 0,
            'pegawai' => 0,
            'kabag' => 0,
            'staff' => 0,
        ]);
    }
}
