<?php

namespace App\Imports;

use App\Models\Shift;
use App\Models\Employee;
use Illuminate\Support\Str;
use App\Models\ShiftSchedule;
use Symfony\Component\Uid\Ulid;
use Maatwebsite\Excel\Concerns\ToModel;

class ShiftScheduleImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $createdUserId = auth()->id();
        $setupUserId = auth()->id();

        // Search shift
        $shiftCode = $row['KD_SHIFT'];
        $shift = Shift::where('code', $shiftCode)->first();

        // Search employee
        $employeeNumber = $row['KD_PGW'];
        $employee = Employee::where('employment_number', $employeeNumber)->first();

        $date = $row['TGL_SHIFT'];

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
            'period' => $row['PERIODE'],
            'leave_note' => null,
            'holiday' => $row['F_LIBUR'],
            'night' => $shift->night_shift,
            'national_holiday' => $row['HARI_NASIONAL'],
        ]);
    }
}
