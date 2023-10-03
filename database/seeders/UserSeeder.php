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
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => 'User ' . $i,
                'email' => 'email' . $i . '@email.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
            ]);
        }
    }
}
