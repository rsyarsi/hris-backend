<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jobs = [
            ['name' => 'PEGAWAI SWASTA', 'active' => 1],
            ['name' => 'PEGAWAI NEGERI SIPIL', 'active' => 1],
            ['name' => 'PELAJAR MAHASISWA', 'active' => 1],
            ['name' => 'PENSIUN', 'active' => 1],
            ['name' => 'TENTARA', 'active' => 1],
            ['name' => 'KEPOLISIAN', 'active' => 1],
        ];
        Job::insert($jobs);
    }
}
