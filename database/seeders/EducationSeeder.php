<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $educations = [
            ['name' => 'SD', 'active' => 1],
            ['name' => 'SMP', 'active' => 1],
            ['name' => 'SMA SEDERAJAT', 'active' => 1],
            ['name' => 'D3', 'active' => 1],
            ['name' => 'S1', 'active' => 1],
            ['name' => 'S2', 'active' => 1],
            ['name' => 'S3', 'active' => 1]
        ];
        Education::insert($educations);
    }
}
