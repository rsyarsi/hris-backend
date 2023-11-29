<?php

namespace App\Rules;

use App\Models\Pph;
use Illuminate\Contracts\Validation\Rule;

class UniquePphRule implements Rule
{
    protected $employeeId;
    protected $period;
    protected $excludeId;

    public function __construct($employeeId, $period, $excludeId = null)
    {
        $this->employeeId = $employeeId;
        $this->period = $period;
        $this->excludeId = $excludeId;
    }

    public function passes($attribute, $value)
    {
        // Query to check uniqueness, excluding the current record (if specified)
        $query = Pph::where('employee_id', $this->employeeId)
                    ->where('period', $this->period);

        if ($this->excludeId) {
            $query->where('id', '!=', $this->excludeId);
        }

        return !$query->exists();
    }

    public function message()
    {
        return 'PPH atas karyawan & periode yang dipilih sudah ada!.';
    }
}
