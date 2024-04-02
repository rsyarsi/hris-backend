<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobVacanciesApplied extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'job_vacancies_applieds';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_id',
        'job_vacancy_id',
        'status',
        'note'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }

    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'id');
    }

    public function jobInterviewForm()
    {
        return $this->hasMany(JobInterviewForm::class, 'job_vacancies_applied_id');
    }
}
