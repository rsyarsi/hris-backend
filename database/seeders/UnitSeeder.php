<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            ['name' => 'TEKNOLOGI INFORMASI DAN KOMUNIKASI', 'active' => 1],
            ['name' => 'ENDOSCOPY', 'active' => 1],
            ['name' => 'IBS', 'active' => 1],
            ['name' => 'ICU', 'active' => 1],
            ['name' => 'IGD', 'active' => 1],
            ['name' => 'KEBIDANAN', 'active' => 1],
            ['name' => 'PICU', 'active' => 1],
            ['name' => 'NICU', 'active' => 1],
            ['name' => 'RANAP LANTAI 5', 'active' => 1],
            ['name' => 'RANAP TB LANTAI 8', 'active' => 1],
            ['name' => 'RANAP LT9', 'active' => 1],
            ['name' => 'RANAP LANTAI 11', 'active' => 1],
            ['name' => 'FARMASI LANTAI 2', 'active' => 1],
            ['name' => 'FARMASI IGD', 'active' => 1],
            ['name' => 'FARMASI LANTAI 6', 'active' => 1],
            ['name' => 'HEMODIALISA', 'active' => 1],
            ['name' => 'OHC', 'active' => 1],
            ['name' => 'POLIKLINIK LANTAI 3', 'active' => 1],
            ['name' => 'POLIKLINIK BPJS', 'active' => 1],
            ['name' => 'POLIKLINIK LANTA 4', 'active' => 1],
            ['name' => 'REHAB MEDIK', 'active' => 1],
            ['name' => 'GIZI', 'active' => 1],
            ['name' => 'LABORATORIUM', 'active' => 1],
            ['name' => 'REKAM MEDIK', 'active' => 1],
            ['name' => 'HUMAS DAN MARKETING', 'active' => 1],
            ['name' => 'CASEMIX', 'active' => 1],
            ['name' => 'ADMINISTRASI DAN LEGAL', 'active' => 1],
            ['name' => 'SDI', 'active' => 1],
            ['name' => 'IPCN', 'active' => 1],
            ['name' => 'PURCHASING', 'active' => 1],
            ['name' => 'KEUANGAN DAN AKUNTANSI', 'active' => 1],
            ['name' => 'KEUANGAN (FRONT OFFICE)', 'active' => 1],
            ['name' => 'GENERAL AFFAIR (ME)', 'active' => 1],
            ['name' => 'GENERAL AFFAIR (DRIVER)', 'active' => 1],
            ['name' => 'GENERAL AFFAIR (KURIR)', 'active' => 1],
            ['name' => 'GENERAL AFFAIR (SARPRAS)', 'active' => 1],
            ['name' => 'GENERAL AFFAIR (KESLING)', 'active' => 1],
            ['name' => 'BOARD OF DIRECTOR', 'active' => 1],
            ['name' => 'RADIOLOGI', 'active' => 1],
        ];
        Unit::insert($units);
    }
}
