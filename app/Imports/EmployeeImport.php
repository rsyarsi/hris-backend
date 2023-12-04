<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Str;
use Symfony\Component\Uid\Ulid;
use Maatwebsite\Excel\Concerns\{ToModel, WithStartRow};

class EmployeeImport implements ToModel, WithStartRow
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
        $ulid = Ulid::generate(); // Generate a ULID
        return new Employee([
            'id' => Str::lower($ulid),
            'name' => $row[0],
            'legal_identity_type_id' => $row[1],
            'legal_identity_number' => $row[2],
            'family_card_number' => $row[3],
            'sex_id' => $row[4],
            'birth_place' => $row[5],
            'birth_date' => $row[6],
            'marital_status_id' => $row[7],
            'religion_id' => $row[8],
            'blood_type' => $row[9],
            'tax_identify_number' => $row[10],
            'email' => $row[11],
            'phone_number' => $row[12],
            'phone_number_country' => $row[13],
            'legal_address' => $row[14],
            'legal_postal_code' => $row[15],
            'legal_province_id' => $row[16],
            'legal_city_id' => $row[17],
            'legal_district_id' => $row[18],
            'legal_village_id' => $row[19],
            'legal_home_phone_number' => $row[20],
            'legal_home_phone_country' => $row[21],
            'current_address' => $row[22],
            'current_postal_code' => $row[23],
            'current_province_id' => $row[24],
            'current_city_id' => $row[25],
            'current_district_id' => $row[26],
            'current_village_id' => $row[27],
            'current_home_phone_number' => $row[28],
            'current_home_phone_country' => $row[29],
            'status_employment_id' => $row[30],
            'position_id' => $row[31],
            'unit_id' => $row[32],
            'department_id' => $row[33],
            'started_at' => $row[34],
            'employment_number' => $row[35],
            'resigned_at' => $row[36],
            'user_id' => $row[37],
            'created_at' => $row[38],
            'updated_at' => $row[39],
            'supervisor_id' => $row[40],
            'manager_id' => $row[41],
            'pin' => $row[42],
            'shift_group_id' => $row[43],
            'kabag_id' => $row[44],
            'rekening_number' => $row[45],
            'bpjs_number' => $row[46],
            'bpjstk_number' => $row[47],
            'status_employee' => $row[48],
        ]);
    }
}
