<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Candidate extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'candidates';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_account_id',
        'first_name',
        'middle_name',
        'last_name',
        'sex_id',
        'legal_identity_type_id',
        'legal_identity_number',
        'legal_address',
        'current_address',
        'home_phone_number',
        'phone_number',
        'email',
        'birth_place',
        'birth_date',
        'age',
        'marital_status_id',
        'ethnic_id',
        'religion_id',
        'tax_identify_number',
        'weight',
        'height',
    ];

    public function identityType()
    {
        return $this->belongsTo(IdentityType::class, 'legal_identity_type_id', 'id');
    }

    public function sex()
    {
        return $this->belongsTo(Sex::class, 'sex_id', 'id');
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id', 'id');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id', 'id');
    }

    public function ethnic()
    {
        return $this->belongsTo(Ethnic::class, 'ethnic_id', 'id');
    }

    public function candidateAccount()
    {
        return $this->belongsTo(CandidateAccount::class, 'candidate_account_id', 'id');
    }

    // public function employeeOrganization()
    // {
    //     return $this->hasMany(EmployeeOrganization::class, 'employee_id');
    // }
}
