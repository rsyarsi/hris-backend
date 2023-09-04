<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'employees';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'name',
        'legal_indentity_type_id',
        'legal_identity_number',
        'family_card_number',
        'sex_id',
        'birth_place',
        'birth_date',
        'marital_status_id',
        'religion_id',
        'blood_type',
        'tax_identify_number',
        'email',
        'phone_number',
        'phone_number_country',
        'legal_address',
        'legal_postal_code',
        'legal_province_id',
        'legal_city_id',
        'legal_district_id',
        'legal_village_id',
        'legal_home_phone_number',
        'legal_home_phone_country',
        'current_address',
        'current_postal_code',
        'current_province_id',
        'current_city_id',
        'current_district_id',
        'current_village_id',
        'current_home_phone_number',
        'current_home_phone_country',
        'status_employment_id',
        'position_id',
        'unit_id',
        'department_id',
        'started_at',
        'employment_number',
        'resigned_at',
        'user_id'
    ];

    public function identityType()
    {
        return $this->belongsTo(IdentityType::class, 'legal_indentity_type_id', 'id');
    }

    public function sex()
    {
        return $this->belongsTo(Sex::class, 'sex_id', 'id');
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class, 'sex_id', 'id');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id', 'id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'legal_province_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'legal_city_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'legal_district_id', 'id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'legal_village_id', 'id');
    }

    public function statusEmployment()
    {
        return $this->belongsTo(StatusEmployment::class, 'status_employment_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // public function districs()
    // {
    //     return $this->hasMany(District::class, 'city_code', 'code');
    // }
}
