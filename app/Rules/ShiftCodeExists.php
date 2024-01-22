<?php

namespace App\Rules;

use App\Models\Shift;
use App\Models\Employee;
use Illuminate\Contracts\Validation\Rule;

class ShiftCodeExists implements Rule
{
    protected $employeeNumber;
    protected $shiftCode;

    public function __construct($employeeNumber, $shiftCode)
    {
        $this->employeeNumber = $employeeNumber;
        $this->shiftCode = $shiftCode;
    }

    public function passes($attribute, $value)
    {
        dd($this->employeeNumber);
        // Query to check uniqueness, excluding the current record (if specified)
        $employee = Employee::where('employment_number', $this->employeeNumber)->first();
        $query = Shift::where('shift_group_id', $employee->shift_group_id)
                        ->where('code', $this->shiftCode)
                        ->exists();

        return !$query->exists();
    }

    public function message()
    {
        return 'Shift Tidak ditemukan!.';
    }
}
