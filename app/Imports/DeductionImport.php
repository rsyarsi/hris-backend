<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use App\Models\{Employee, Deduction};
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithStartRow, WithValidation};


class DeductionImport implements ToModel, WithStartRow, WithValidation
{
    use Importable;
    protected $importedData = [];
    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '0' => 'required|exists:employees,employment_number',
            '1' => 'required|numeric',
            '2' => 'required|max:255',
            '3' => 'required|numeric',
            '4' => 'required|date_format:Y-m',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.exists' => 'Karyawan tidak ditemukan pada row :attribute.',
            '4.date_format' => 'Format tidak sesuai, contoh format yang benar 2024-01 pada row :attribute.',
        ];
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
            $deductions[] = Deduction::create([
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

        $this->importedData[] = $deductions;
        return $deductions;
    }

    public function getImportedData()
    {
        return $this->importedData;
    }
}
