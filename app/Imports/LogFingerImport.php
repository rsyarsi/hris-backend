<?php

namespace App\Imports;

use Illuminate\Support\Str;
use App\Models\{Employee, LogFinger};
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithStartRow, WithValidation};

class LogFingerImport implements ToModel, WithStartRow, WithValidation
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
            '1' => 'required|max:45',
            '2' => 'required|date_format:Y-m-d H:i:s',
            '3' => 'required|in:1,0',
            '4' => 'required|max:45',
            '5' => 'required|date_format:Y-m-d H:i:s',
            '6' => 'required|date_format:Y-m-d H:i:s',
            '7' => 'required|date_format:Y-m-d H:i:s',
            '8' => 'required|max:15',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.exists' => 'Karyawan tidak ditemukan pada row :attribute.',
            '2.date_format' => 'Format tidak sesuai, contoh format yang benar 2024-01-01 07:50:00 pada row :attribute.',
            '5.date_format' => 'Format tidak sesuai, contoh format yang benar 2024-01-01 07:50:00 pada row :attribute.',
            '6.date_format' => 'Format tidak sesuai, contoh format yang benar 2024-01-01 07:50:00 pada row :attribute.',
            '7.date_format' => 'Format tidak sesuai, contoh format yang benar 2024-01-01 07:50:00 pada row :attribute.',
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
        $existingEntry = LogFinger::where([
            'employee_id' => $employee->id,
            'datetime' => $row[2],
            'time_in' => $row[5],
            'time_out' => $row[6],
            'tgl_log' => $row[7],
        ])->first();

        // If the entry exists, skip it
        if ($existingEntry) {
            return null; // Skip this row
        } else {
            $uuid = Str::uuid(); // Generate a UUID
            $logFinger = LogFinger::create([
                'id' => $uuid,
                'employee_id' => $employee->id,
                'code_sn_finger' => $row[1],
                'datetime' => $row[2],
                'manual' => $row[3],
                'code_pin' => $row[4],
                'user_manual_id' => auth()->id(),
                'input_manual_at' => now(),
                'time_in' => $row[5],
                'time_out' => $row[6],
                'tgl_log' => $row[7],
                'absen_type' => $row[8],
            ]);
        }

        $this->importedData[] = $logFinger;
        return $logFinger;
    }

    public function getImportedData()
    {
        return $this->importedData;
    }
}
