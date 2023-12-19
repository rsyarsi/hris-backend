<?php

namespace App\Rules;

use Carbon\Carbon;
use App\Models\Leave;
use Illuminate\Contracts\Validation\Rule;

class NotOverlappingPermissionsLeaves implements Rule
{
    protected $employeeId;
    protected $fromDate;
    protected $toDate;
    protected $recordId; // New property to store the record ID being updated

    public function __construct($employeeId, $fromDate, $toDate, $recordId = null)
    {
        $this->employeeId = $employeeId;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->recordId = $recordId; // Set the record ID
    }

    public function passes($attribute, $value)
    {
        $fromDate = Carbon::parse($this->fromDate);
        $toDate = Carbon::parse($this->toDate);

        if ($fromDate >= $toDate) {
            return false; // Disallow when from_date is greater than or equal to to_date
        }

        // Allow a difference of one minute (60 seconds)
        $minimumDifference = 60; // 60 seconds

        $query = Leave::where('employee_id', $this->employeeId)
            ->where(function ($query) use ($fromDate, $toDate, $minimumDifference) {
                $query->where(function ($q) use ($fromDate, $toDate, $minimumDifference) {
                    $q->where('from_date', '<', $toDate->subSeconds($minimumDifference))
                        ->where('to_date', '>', $fromDate->addSeconds($minimumDifference));
                });
            });

        // Exclude the current record from the validation check when updating
        if ($this->recordId) {
            $query->where('id', '!=', $this->recordId);
        }

        $overlappingPermissions = $query->count();

        return $overlappingPermissions === 0;
    }

    public function message()
    {
        return 'Karyawan sudah mengajukan leaves di hari yang sama!.';
    }
}
