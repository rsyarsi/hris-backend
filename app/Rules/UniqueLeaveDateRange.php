<?php

namespace App\Rules;

use App\Models\Leave;
use Illuminate\Contracts\Validation\Rule;

class UniqueLeaveDateRange implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $applyRule;

    public function __construct($applyRule = true)
    {
        $this->applyRule = $applyRule;
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
        $fromDate = request('from_date');
        $toDate = request('to_date');
        $employeeId = request('employee_id');

        // Check if the date range already exists for the given employee
        return !Leave::where('employee_id', $employeeId)
                    ->where(function ($query) use ($fromDate, $toDate) {
                        $query->whereBetween('from_date', [$fromDate, $toDate])
                                ->orWhereBetween('to_date', [$fromDate, $toDate]);
                                // ->whereNotIn('leave_status_id', [6,7,8,9,10]);
                    })
                    ->where('leave_status_id', 1)
                    ->orWhere('leave_status_id', 2)
                    ->orWhere('leave_status_id', 3)
                    ->orWhere('leave_status_id', 4)
                    ->orWhere('leave_status_id', 5)
                    ->exists();
    }

    public function message()
    {
        return 'Tanggal yang dipilih sudah ada data leave!';
    }
}
