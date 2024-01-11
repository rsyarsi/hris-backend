<?php

namespace Database\Seeders;

use App\Models\ShiftGroup;
use Illuminate\Database\Seeder;
use Symfony\Component\Uid\Ulid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShiftGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shiftGroups = [
            ['name' => 'NON SHIFT', 'hour' => 0 , 'day' => 0, 'type' => 'NSHIFT'],
            ['name' => 'HEMODIALISA', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'OHC', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'POLIKLINIK', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'REHAB MEDIK', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'GIZI', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'LABORATORIUM', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'REKAM MEDIK', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'HUMAS DAN MARKETING', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'GENERAL AFFAIR(UMUM)', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'TIK', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'RADIOLOGI', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'FARMASI', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'RANAP LT 5', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'RANAP LT 8', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'RANAP LT 9', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'RANAP LT 11', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'HBOT', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'NICU PICU', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'KEBIDANAN', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'IBS', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'ENDOSCOPY', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'CATHLAB', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'IGD', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'ICU', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'ODC', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'KEUANGAN (KASIR RAJAL)', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
            ['name' => 'KEUANGAN (BILLING RANAP)', 'hour' => 0 , 'day' => 0, 'type' => 'SHIFT'],
        ];
        foreach ($shiftGroups as $shiftGroup) {
            // Generate a ULID for each record
            $shiftGroup['id'] = Ulid::generate(); // Generate a ULID
            ShiftGroup::insert($shiftGroup);
        }
    }
}
