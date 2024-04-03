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
            'communication_skills' => 'required|numeric|digits_between:1,5',
            'confidence_and_eye_contact' => 'required|numeric|digits_between:1,5',
            'coherent_problem_solving' => 'required|numeric|digits_between:1,5',
            'active_listening_and_feedback' => 'required|numeric|digits_between:1,5',
            'task_estimation_accuracy' => 'required|numeric|digits_between:1,5',
            'schedule_development' => 'required|numeric|digits_between:1,5',
            'resource_prioritization' => 'required|numeric|digits_between:1,5',
            'quick_problem_solving' => 'required|numeric|digits_between:1,5',
            'decision_making_under_uncertainty' => 'required|numeric|digits_between:1,5',
            'performance_under_pressure' => 'required|numeric|digits_between:1,5',
            'positive_situation_evaluation' => 'required|numeric|digits_between:1,5',
            'efficiency_improvement_solutions' => 'required|numeric|digits_between:1,5',
            'critical_problem_analysis' => 'required|numeric|digits_between:1,5',
            'personnel_motivation' => 'required|numeric|digits_between:1,5',
            'personal_performance_improvement' => 'required|numeric|digits_between:1,5',
            'strategic_communication' => 'required|numeric|digits_between:1,5',
            'extra_work_initiative' => 'required|numeric|digits_between:1,5',
            'goal_redirection' => 'required|numeric|digits_between:1,5',
            'ethical_behavior' => 'required|numeric|digits_between:1,5',
            'team_cooperation' => 'required|numeric|digits_between:1,5',
            'influencing_skills' => 'required|numeric|digits_between:1,5',
            'strategic_planning' => 'required|numeric|digits_between:1,5',
            'conflict_resolution' => 'required|numeric|digits_between:1,5',
            'additional_comments' => 'required|numeric|digits_between:1,5',
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
