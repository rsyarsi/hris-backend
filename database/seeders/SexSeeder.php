<?php

namespace Database\Seeders;

use App\Models\Sex;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sex::create(['name' => 'LAKI-LAKI']);
        Sex::create(['name' => 'PEREMPUAN']);
    }
}
