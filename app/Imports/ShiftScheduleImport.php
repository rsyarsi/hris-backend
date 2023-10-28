<?php

namespace App\Imports;

use App\Models\{Shift, Employee};
use Illuminate\Support\Str;
use App\Models\ShiftSchedule;
use Symfony\Component\Uid\Ulid;
use Maatwebsite\Excel\Concerns\ToModel;

class ShiftScheduleImport implements ToModel
{
    private $rowCount = 0; // Initialize a counter

    public function getRowCount()
    {
        return $this->rowCount;
    }

    public function startRow(): int
    {
        return 2; // Skip the header row
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $createdUserId = auth()->id();
        $setupUserId = auth()->id();
        $date = $row[3];

        // Search shift
        $shiftCode = $row[1];
        $shift = Shift::where('code', $shiftCode)->get();

        // Search employee
        $employeeNumber = $row[0];
        $employee = Employee::where('employment_number', $employeeNumber)->get();
        // Check if the employee is found, otherwise skip this row
        if (!$employee) {
            return null; // Skip this row
        }

        $ulid = Ulid::generate(); // Generate a ULID
        return new ShiftSchedule([
            'id' => Str::lower($ulid),
            'employee_id' => $employee->id,
            'shift_id' => $shift->id,
            'date' => $date->format('Y-m-d'),
            'time_in' => $date->format('Y-m-d') . ' ' . $shift->in_time,
            'time_out' => $date->format('Y-m-d') . ' ' . $shift->out_time,
            'late_note' => null,
            'shift_exchange_id' => null,
            'user_exchange_id' => null,
            'user_exchange_at' => null,
            'created_user_id' => $createdUserId,
            'updated_user_id' => null, // You may need to set this as per your requirements
            'setup_user_id' => $setupUserId,
            'setup_at' => now(), // You can customize the setup_at value
            'period' => $row[2],
            'leave_note' => null,
            'holiday' => $row[4],
            'night' => $shift->night_shift,
            'national_holiday' => $row[5],
        ]);
    }

}
