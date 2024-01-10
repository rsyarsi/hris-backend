<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $positions = [
            ['name' => 'DIREKTUR UTAMA', 'active' => 1],
            ['name' => 'DIREKTUR MEDIS', 'active' => 1],
            ['name' => 'DIREKTUR SDI', 'active' => 1],
            ['name' => 'KEPALA BAGIAN', 'active' => 1],
            ['name' => 'KEPALA INSTALASI', 'active' => 1],
            ['name' => 'SUPERVISOR', 'active' => 1],
            ['name' => 'STAFF PELAKSANA', 'active' => 1],
            ['name' => 'MANAGER', 'active' => 1],
        ];
        Position::insert($positions);
    }
}
