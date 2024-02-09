<?php

namespace App\Exports;

use App\Models\LogFinger;
use Maatwebsite\Excel\Concerns\FromCollection;

class LogFingerExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return LogFinger::all();
    }
}
