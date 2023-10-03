<?php

namespace Database\Seeders;

use App\Models\StatusEmployment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusEmploymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 3; $i++) {
            StatusEmployment::create([
                'name' => 'Status Employment ' . $i,
                'active' => rand(0, 1),
            ]);
        }
    }
}
