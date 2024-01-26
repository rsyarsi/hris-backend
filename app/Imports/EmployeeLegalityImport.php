<?php

namespace App\Imports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use App\Models\{Employee, EmployeeLegality};
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithStartRow, WithValidation};

class EmployeeLegalityImport implements ToModel, WithStartRow, WithValidation
{
    use Importable;

    public function startRow(): int
    {
        return 2;
    }

    public function rules(): array
    {
        return [
            '0' => 'required|exists:employees,employment_number',
            '1' => 'required|exists:mlegalitytypes,id',
            '2' => 'required',
            '3' => 'required',
            '4' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '0.exists' => 'Karyawan tidak ditemukan :attribute.',
            '1.exists' => 'Legality Type tidak ditemukan :attribute.',
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
        $dateStart = $row[2];
        $dateEnd = $row[3];
        $dateStart = Carbon::createFromFormat('d-m-Y', $row[2])->format('Y-m-d');
        // Set the locale to English for proper month parsing
        Carbon::setLocale('id');
        $dateEnd = Carbon::parse($row[3])->format('Y-m-d');
        // $dateEnd = Carbon::createFromFormat('d F Y', $row[3])->format('Y-m-d');

        $employee = Employee::where('employment_number', $employeeNumber)->first();
        if (!$employee) {
            return; // reject the request if employee not found
        }

        $ulid = Ulid::generate(); // Generate a ULID
        return new EmployeeLegality([
            'id' => Str::lower($ulid),
            'employee_id' => $employee->id,
            'legality_type_id' => $row[1],
            'started_at' => $dateStart,
            'ended_at' => $dateEnd,
            'no_str' => $row[4],
        ]);
    }
}
