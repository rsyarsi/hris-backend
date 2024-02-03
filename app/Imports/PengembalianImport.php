<?php

namespace App\Imports;

use Illuminate\Support\Str;
use App\Models\{Employee, Pengembalian};
use Symfony\Component\Uid\Ulid;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithStartRow, WithValidation};

class PengembalianImport implements ToModel, WithStartRow, WithValidation
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
            '2' => 'required|date_format:Y-m',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.exists' => 'Karyawan tidak ditemukan pada row :attribute.',
            '2.date_format' => 'Format tidak sesuai, contoh format yang benar 2024-01 pada row :attribute.',
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
        if (!$employee) {
            return null; // Skip this row
        }

        // Check if the entry already exists in the shift_schedules table
        $existingEntry = Pengembalian::where([
            'employee_id' => $employee->id,
            'amount' => $row[1],
            'payroll_period' => $row[2],
        ])->first();

        // If the entry exists, skip it
        if ($existingEntry) {
            return null; // Skip this row
        }
        $ulid = Ulid::generate(); // Generate a ULID
        $pengembalian = Pengembalian::create([
            'id' => Str::lower($ulid),
            'employee_id' => $employee->id,
            'amount' => $row[1],
            'payroll_period' => $row[2],
            'user_created_id' => auth()->id(),
        ]);

        $this->importedData[] = $pengembalian;
        return $pengembalian;
    }

    public function getImportedData()
    {
        return $this->importedData;
    }
}
