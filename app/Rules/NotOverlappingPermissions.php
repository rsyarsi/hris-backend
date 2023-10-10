<?php

namespace App\Rules;

use App\Models\Leave;
use Illuminate\Contracts\Validation\Rule;

class NotOverlappingPermissions implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $employeeId;
    protected $fromDate;
    protected $toDate;

    public function __construct($employeeId, $fromDate, $toDate)
    {
        $this->employeeId = $employeeId;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
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
        // Query the database to check for overlapping permissions
        $overlappingPermissions = Leave::where('employee_id', $this->employeeId)
            ->where(function ($query) {
                $query->whereBetween('from_date', [$this->fromDate, $this->toDate])
                    ->orWhereBetween('to_date', [$this->fromDate, $this->toDate]);
            })
            ->count();

        return $overlappingPermissions === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Employee already has a permission for the same date range.';
    }
}
