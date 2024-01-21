<?php

namespace App\Rules;

use App\Models\ShiftScheduleExchange;
use Illuminate\Contracts\Validation\Rule;

class UniqueShiftScheduleExchangeDateRange implements Rule
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
        $employeeId = request('employe_requested_id');
        $shiftScheduleDateRequested = request('shift_schedule_date_requested');

        // Check if the date range already exists for the given employee
        return !ShiftScheduleExchange::where('employe_requested_id', $employeeId)
                    ->where('shift_schedule_date_requested', $shiftScheduleDateRequested)
                    ->exists();
    }

    public function message()
    {
        return 'Tanggal yang dipilih sudah ada data tukar dinas!';
    }
}
