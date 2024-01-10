<?php

namespace Database\Seeders;

use App\Models\MaritalStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaritalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $maritalStatus = [
            ['name' => 'BELUM MENIKAH', 'active' => 1],
            ['name' => 'SUDAH MENIKAH', 'active' => 1],
            ['name' => 'CERAI MATI', 'active' => 1],
            ['name' => 'JANDA', 'active' => 1],
            ['name' => 'DUDA', 'active' => 1],
        ];
        MaritalStatus::insert($maritalStatus);
    }
}
