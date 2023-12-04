<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use App\Models\{Employee, Deduction};
use Maatwebsite\Excel\Concerns\{ToModel, WithStartRow};


class DeductionImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $employeeNumber = $row[0];
        $employee = Employee::where('employment_number', $employeeNumber)->first();

        // Check if the employee exists
        if (!$employee) {
            return null; // Skip this row
        }

        $tenor = (int) $row[3];

        // Validate that tenor is greater than or equal to 1
        if ($tenor < 1) {
            return null; // Skip this row
        }

        $deductions = [];

        // Loop through the tenor and create multiple entries
        for ($i = 1; $i <= $tenor; $i++) {
            $ulid = Ulid::generate(); // Generate a ULID
            $deductions[] = new Deduction([
                'id' => Str::lower($ulid),
                'employee_id' => $employee->id,
                'nilai' => $row[1] / $tenor, // Divide the nilai by tenor
                'keterangan' => $row[2],
                'tenor' => $i, // Increment the tenor for each created entry
                'period' => Carbon::parse($row[4])->addMonths($i - 1)->format('Y-m'),
                'pembayaran' => 0,
                'sisa' => 0,
                'kode_lunas' => null,
            ]);
        }

        return $deductions;
    }
}
