<?php

namespace App\Rules;

use App\Models\Pengembalian;
use Illuminate\Contracts\Validation\Rule;

class UniquePengembalianPeriod implements Rule
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
        $periodPayroll = request('payroll_period');
        $employeeId = request('employee_id');

        // Check if the date range already exists for the given employee
        return !Pengembalian::where('employee_id', $employeeId)
                    ->where('payroll_period', $periodPayroll)
                    ->exists();
    }

    public function message()
    {
        return 'Periode yang dipilih sudah ada data Pengembalian!';
    }
}
