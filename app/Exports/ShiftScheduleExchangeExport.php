<?php

namespace App\Exports;

use App\Models\ShiftScheduleExchange;
use Maatwebsite\Excel\Concerns\FromCollection;

class ShiftScheduleExchangeExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ShiftScheduleExchange::all();
    }
}
