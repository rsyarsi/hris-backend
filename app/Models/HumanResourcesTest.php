<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HumanResourcesTest extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'human_resources_tests';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_id',
        'job_vacancy_id',
        'name',
        'applied_position',
        'date',
        'source_of_info',
        'motivation',
        'self_assessment',
        'desired_position',
        'coping_with_department_change',
        'previous_job_challenges',
        'reason_for_resignation',
        'conflict_management',
        'stress_management',
        'overtime_availability',
        'handling_complaints',
        'salary_expectation',
        'benefits_facilities'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }

    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'id');
    }
}
