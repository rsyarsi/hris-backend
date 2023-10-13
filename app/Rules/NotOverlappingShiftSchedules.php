<?php

namespace App\Rules;

use App\Models\ShiftSchedule;
use Illuminate\Contracts\Validation\Rule;

class NotOverlappingShiftSchedules implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Retrieve the employee_id and start_date from the input data
        $employeeId = request()->input('employee_id');
        $startDate = request()->input('start_date');
        $date = request()->input('date');

        // Check if a record with the same employee_id and start_date already exists
        $count = ShiftSchedule::where('employee_id', $employeeId)
            ->where('date', $startDate)
            ->orWhere('date', $date)
            ->count();

        return $count === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A shift schedule with the same date already exists for this employee.';
    }
}
