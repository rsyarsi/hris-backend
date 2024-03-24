<?php

namespace App\Imports;

use App\Models\Ethnic;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\{ToModel, WithStartRow};

class EthnicImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Ethnic([
            'name' => Str::upper($row[0]),
            'status' => $row[1],
        ]);
    }
}
