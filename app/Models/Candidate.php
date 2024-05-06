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
        'file_url',
        'file_path',
        'file_disk',
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

    public function emergencyContact()
    {
        return $this->hasMany(EmergencyContactCandidate::class, 'candidate_id');
    }

    public function familyInformation()
    {
        return $this->hasMany(FamilyInformationCandidate::class, 'candidate_id');
    }

    public function educationBackground()
    {
        return $this->hasMany(EducationBackgroundCandidate::class, 'candidate_id');
    }

    public function organizationExperience()
    {
        return $this->hasMany(OrganizationExperienceCandidate::class, 'candidate_id');
    }

    public function expertiseCertification()
    {
        return $this->hasMany(ExpertiseCertificationCandidate::class, 'candidate_id');
    }

    public function coursesTraining()
    {
        return $this->hasMany(CoursesTrainingCandidate::class, 'candidate_id');
    }

    public function foreignLanguage()
    {
        return $this->hasMany(ForeignLanguageCandidate::class, 'candidate_id');
    }

    public function workExperience()
    {
        return $this->hasMany(WorkExperienceCandidate::class, 'candidate_id');
    }

    public function hospitalConnection()
    {
        return $this->hasMany(HospitalConnectionCandidate::class, 'candidate_id');
    }

    public function selfPerspective()
    {
        return $this->hasOne(SelfPerspectiveCandidate::class, 'candidate_id', 'id');
    }

    public function additionalInformation()
    {
        return $this->hasOne(AdditionalInformationCandidate::class, 'candidate_id', 'id');
    }

    public function humanResourcesTest()
    {
        return $this->hasMany(HumanResourcesTest::class, 'candidate_id');
    }

    public function jobVacanciesApplied()
    {
        return $this->hasMany(JobVacanciesApplied::class, 'candidate_id');
    }

    public function jobInterviewForm()
    {
        return $this->hasMany(JobInterviewForm::class, 'candidate_id');
    }
}
