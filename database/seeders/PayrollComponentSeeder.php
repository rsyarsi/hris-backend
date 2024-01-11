<?php

namespace Database\Seeders;

use App\Models\PayrollComponent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PayrollComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payrollComponents = [
            ['name' => 'GAJI POKOK', 'active' => 1, 'group_component_payroll' => 'FIX INCOME', 'order' => 1],
            ['name' => 'TUNJANGAN TRANSPORTASI', 'active' => 1, 'group_component_payroll' => 'FIX INCOME', 'order' => 2],
            ['name' => 'UANG MAKAN', 'active' => 1, 'group_component_payroll' => 'FIX INCOME', 'order' => 3],
            ['name' => 'TUNJANGAN KEMAHALAN', 'active' => 1, 'group_component_payroll' => 'FIX INCOME', 'order' => 4],
            ['name' => 'TUNJANGAN HDM', 'active' => 1, 'group_component_payroll' => 'TUNJANGAN', 'order' => 5],
            ['name' => 'TUNJANGAN JABATAN', 'active' => 1, 'group_component_payroll' => 'TUNJANGAN', 'order' => 6],
            ['name' => 'DINAS MALAM', 'active' => 1, 'group_component_payroll' => 'TUNJANGAN', 'order' => 7],
            ['name' => 'TUNJANGAN PPR', 'active' => 1, 'group_component_payroll' => 'TUNJANGAN', 'order' => 8],
            ['name' => 'INSENTIVE KHUSUS', 'active' => 1, 'group_component_payroll' => 'TUNJANGAN', 'order' => 9],
            ['name' => 'TUNJANGAN EXTRA FOODING', 'active' => 1, 'group_component_payroll' => 'TUNJANGAN', 'order' => 10],
            ['name' => 'LEMBUR', 'active' => 1, 'group_component_payroll' => 'TUNJANGAN', 'order' => 11],
            ['name' => 'PENGEMBALIAN POTONGAN', 'active' => 1, 'group_component_payroll' => 'TUNJANGAN', 'order' => 12],
            ['name' => 'JKK', 'active' => 1, 'group_component_payroll' => 'KEWAJIBAN PERUSAHAAN', 'order' => 14],
            ['name' => 'JKM', 'active' => 1, 'group_component_payroll' => 'KEWAJIBAN PERUSAHAAN', 'order' => 15],
            ['name' => 'JHT', 'active' => 1, 'group_component_payroll' => 'KEWAJIBAN PERUSAHAAN', 'order' => 16],
            ['name' => 'JP', 'active' => 1, 'group_component_payroll' => 'KEWAJIBAN PERUSAHAAN', 'order' => 17],
            ['name' => 'BPJS KESEHATAN', 'active' => 1, 'group_component_payroll' => 'KEWAJIBAN PERUSAHAAN', 'order' => 18],
            ['name' => 'JHT KARYAWAN', 'active' => 1, 'group_component_payroll' => 'KEWAJIBAN KARYAWAN', 'order' => 19],
            ['name' => 'JP KARYAWAN', 'active' => 1, 'group_component_payroll' => 'KEWAJIBAN KARYAWAN', 'order' => 20],
            ['name' => 'BPJS KESEHATAN KARYAWAN', 'active' => 1, 'group_component_payroll' => 'KEWAJIBAN KARYAWAN', 'order' => 21],
            ['name' => 'PPH 21', 'active' => 1, 'group_component_payroll' => 'KEWAJIBAN KARYAWAN', 'order' => 22],
            ['name' => 'ZAKAT', 'active' => 1, 'group_component_payroll' => 'KEWAJIBAN KARYAWAN', 'order' => 23],
            ['name' => 'POTONGAN KARYAWAN', 'active' => 1, 'group_component_payroll' => 'KEWAJIBAN KARYAWAN', 'order' => 24],
        ];
        PayrollComponent::insert($payrollComponents);
    }
}
