<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $religions = [
            ['name' => 'ISLAM', 'active' => 1],
            ['name' => 'KRISTEN', 'active' => 1],
            ['name' => 'HINDU', 'active' => 1],
            ['name' => 'BUDHA', 'active' => 1],
            ['name' => 'KONGHUCU', 'active' => 1]
        ];
        Religion::insert($religions);
    }
}
