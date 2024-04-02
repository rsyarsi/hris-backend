<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobInterviewForm extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'job_interview_forms';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_id',
        'job_vacancy_id',
        'interviewer_id',
        'job_vacancies_applied_id',
        'date',
        'communication_skills',
        'confidence_and_eye_contact',
        'coherent_problem_solving',
        'active_listening_and_feedback',
        'task_estimation_accuracy',
        'schedule_development',
        'resource_prioritization',
        'quick_problem_solving',
        'decision_making_under_uncertainty',
        'performance_under_pressure',
        'positive_situation_evaluation',
        'efficiency_improvement_solutions',
        'critical_problem_analysis',
        'personnel_motivation',
        'personal_performance_improvement',
        'strategic_communication',
        'extra_work_initiative',
        'goal_redirection',
        'ethical_behavior',
        'team_cooperation',
        'influencing_skills',
        'strategic_planning',
        'conflict_resolution',
        'additional_comments',
        'status',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }

    public function jobVacancy()
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'id');
    }

    public function interviewer()
    {
        return $this->belongsTo(Employee::class, 'interviewer_id', 'id');
    }

    public function jobVacanciesApplied()
    {
        return $this->belongsTo(JobVacanciesApplied::class, 'job_vacancies_applied_id', 'id');
    }
}
