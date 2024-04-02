<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class JobInterviewFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'candidate_id' => 'required|exists:candidates,id',
            'job_vacancy_id' => 'required|exists:job_vacancies,id',
            'interviewer_id' => [
                'required',
                'exists:employees,id',
                Rule::unique('job_interview_forms')->where(function ($query) {
                    return $query->where('job_vacancies_applied_id', $this->input('job_vacancies_applied_id'));
                })->ignore($this->route('job_interview_form'))
            ],
            'job_vacancies_applied_id' => 'required|exists:job_vacancies_applieds,id',
            'date' => 'required|date',
            'communication_skills' => 'required|string',
            'confidence_and_eye_contact' => 'required|string',
            'coherent_problem_solving' => 'required|string',
            'active_listening_and_feedback' => 'required|string',
            'task_estimation_accuracy' => 'required|string',
            'schedule_development' => 'required|string',
            'resource_prioritization' => 'required|string',
            'quick_problem_solving' => 'required|string',
            'decision_making_under_uncertainty' => 'required|string',
            'performance_under_pressure' => 'required|string',
            'positive_situation_evaluation' => 'required|string',
            'efficiency_improvement_solutions' => 'required|string',
            'critical_problem_analysis' => 'required|string',
            'personnel_motivation' => 'required|string',
            'personal_performance_improvement' => 'required|string',
            'strategic_communication' => 'required|string',
            'extra_work_initiative' => 'required|string',
            'goal_redirection' => 'required|string',
            'ethical_behavior' => 'required|string',
            'team_cooperation' => 'required|string',
            'influencing_skills' => 'required|string',
            'strategic_planning' => 'required|string',
            'conflict_resolution' => 'required|string',
            'additional_comments' => 'required|string',
            'status' => 'required|in:HIRE,RECOMENDED-OTHER-POSITION,POSIBLE-INTEREST,REJECT',
        ];
    }

    protected function failedValidation($validator)
    {
        $response = [
            'message' => 'Validation error',
            'error' => true,
            'code' => 422,
            'data' => $validator->errors(),
        ];

        throw new ValidationException(
            $validator,
            response()->json($response, 422)
        );
    }
}
