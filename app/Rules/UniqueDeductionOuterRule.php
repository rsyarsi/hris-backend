<?php

namespace App\Rules;

use App\Models\Deduction;
use App\Models\Employee;
use Illuminate\Contracts\Validation\Rule;

class UniqueDeductionOuterRule implements Rule
{
    protected $employeeNumber;
    protected $period;
    protected $excludeId;

    public function __construct($employeeNumber, $period, $excludeId = null)
    {
        $this->employeeNumber = $employeeNumber;
        $this->period = $period;
        $this->excludeId = $excludeId;
    }

    public function passes($attribute, $value)
    {
        // Query to check uniqueness, excluding the current record (if specified)
        $employee = Employee::where('employment_number', $this->employeeNumber)->first();
        $query = Deduction::where('employee_id', $employee->id)
                    ->where('period', $this->period);

        if ($this->excludeId) {
            $query->where('id', '!=', $this->excludeId);
        }

        return !$query->exists();
    }

    public function message()
    {
        return 'Pengurangan atas karyawan & periode yang dipilih sudah ada!.';
    }
}
