<?php

namespace App\Exports;

use App\Models\TimesheetOvertime;
use Maatwebsite\Excel\Concerns\FromCollection;

class TimesheetOvertimeExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return TimesheetOvertime::all();
    }
}
