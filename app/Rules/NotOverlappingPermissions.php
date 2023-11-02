<?php

namespace App\Rules;

use Carbon\Carbon;
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
        $fromDate = Carbon::parse($this->fromDate);
        $toDate = Carbon::parse($this->toDate);

        if ($fromDate >= $toDate) {
            return false; // Disallow when from_date is greater than or equal to to_date
        }

        // Allow a difference of one minute (60 seconds)
        $minimumDifference = 60; // 60 seconds

        $overlappingPermissions = Leave::where('employee_id', $this->employeeId)
            ->where(function ($query) use ($fromDate, $toDate, $minimumDifference) {
                $query->where(function ($q) use ($fromDate, $toDate, $minimumDifference) {
                    $q->where('from_date', '<', $toDate->subSeconds($minimumDifference))
                    ->where('to_date', '>', $fromDate->addSeconds($minimumDifference));
                });
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
