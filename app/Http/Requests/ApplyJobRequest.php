<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
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
            'first_name_candidate' => 'required', // candidate table start
            'middle_name_candidate' => 'required',
            'last_name_candidate' => 'required',
            'sex_id_candidate' => 'required',
            'legal_identity_number_candidate' => 'required',
            'legal_address_candidate' => 'required',
            'current_address_candidate' => 'required',
            'phone_number_candidate' => 'required',
            'home_phone_number_candidate' => 'required',
            'email_candidate' => 'required',
            'birth_place_candidate' => 'required',
            'birth_date_candidate' => 'required',
            'marital_status_id_candidate' => 'required',
            'ethnic_id_candidate' => 'required',
            'religion_id_candidate' => 'required',
            'tax_identify_number_candidate' => 'required',
            'weight_candidate' => 'required',
            'height_candidate' => 'required',
            'file_photo' => 'required|image|mimes:png,jpg,jpeg',
            'file_cv' => 'required|file|mimes:pdf', // candidate table end

            'relationship_id_emergency_contact' => 'required', // emergency contact table start
            'name_emergency_contact' => 'required',
            'sex_id_emergency_contact' => 'required',
            'phone_number_emergency_contact' => 'required',
            'address_emergency_contact' => 'required', // emergency contact table end

            'relationship_id_family_information' => 'required|array|exists:mrelationships,id', // family informations start
            'relationship_id_family_information.*' => 'required|exists:mrelationships,id',
            'name_family_information' => 'required|array',
            'name_family_information.*' => 'required|string|max:100',
            'sex_id_family_information' => 'required|array',
            'sex_id_family_information.*' => 'required|string|in:1,2',
            'birth_place_family_information' => 'required|array',
            'birth_place_family_information.*' => 'required|string|max:100',
            'birth_date_family_information' => 'required|array',
            'birth_date_family_information.*' => 'required|date',
            'education_id_family_information' => 'required|array|exists:meducations,id',
            'education_id_family_information.*' => 'required|exists:meducations,id',
            'job_id_family_information' => 'required|array|exists:mjobs,id',
            'job_id_family_information.*' => 'required|exists:mjobs,id', // family informations end

            'relationship_id_family_member' => 'required|array|exists:mrelationships,id', // family members start
            'relationship_id_family_member.*' => 'required|exists:mrelationships,id',
            'name_family_member' => 'required|array',
            'name_family_member.*' => 'required|string|max:100',
            'sex_id_family_member' => 'required|array',
            'sex_id_family_member.*' => 'required|string|in:1,2',
            'birth_place_family_member' => 'required|array',
            'birth_place_family_member.*' => 'required|string|max:100',
            'birth_date_family_member' => 'required|array',
            'birth_date_family_member.*' => 'required|date',
            'education_id_family_member' => 'required|array|exists:meducations,id',
            'education_id_family_member.*' => 'required|exists:meducations,id',
            'job_id_family_member' => 'required|array|exists:mjobs,id',
            'job_id_family_member.*' => 'required|exists:mjobs,id', // family members end

            'education_id_education_background' => 'required|exists:meducations,id', // education background start
            'institution_name_education_background' => 'required',
            'major_education_background' => 'required',
            'started_year_education_background' => 'required',
            'ended_year_education_background' => 'required',
            'final_score_education_background' => 'required',
            'file_ijasah_education_background' => 'required|file|mimes:pdf', // education background end

            'organization_name' => 'required|array', // organization experiences start
            'organization_name.*' => 'required|max:100',
            'position_organization_experience' => 'required|array',
            'position_organization_experience.*' => 'required|max:100',
            'year_organization_experience' => 'required|array',
            'year_organization_experience.*' => 'required|date_format:Y',
            'description_organization_experience' => 'required|array',
            'description_organization_experience.*' => 'required', // organization experiences end

            'type_of_expertise' => 'required|array', // expertise certification start
            'type_of_expertise.*' => 'required|max:100',
            'qualification_type' => 'required|array',
            'qualification_type.*' => 'required|max:100',
            'given_by_expertise_certification' => 'required|array',
            'given_by_expertise_certification.*' => 'required|max:100',
            'year_expertise_certification' => 'required|array',
            'year_expertise_certification.*' => 'required|date_format:Y',
            'description_expertise_certification' => 'required|array',
            'description_expertise_certification.*' => 'required', // expertise certification end

            'type_of_training' => 'required|array', // courses training start
            'type_of_training.*' => 'required|max:100',
            'level_courses_training' => 'required|array',
            'level_courses_training.*' => 'required|max:100',
            'organized_by_courses_training' => 'required|array',
            'organized_by_courses_training.*' => 'required|max:100',
            'year_courses_training' => 'required|array',
            'year_courses_training.*' => 'required|date_format:Y',
            'description_courses_training' => 'required|array',
            'description_courses_training.*' => 'required', // courses training end

            'company_work_experience' => 'required|array', // work experience start
            'company_work_experience.*' => 'required|max:100',
            'position_work_experience' => 'required|array',
            'position_work_experience.*' => 'required|max:100',
            'from_date_work_experience' => 'required|array',
            'from_date_work_experience.*' => 'required|date',
            'to_date_work_experience' => 'required|array',
            'to_date_work_experience.*' => 'required|date',
            'location_work_experience' => 'required|array',
            'location_work_experience.*' => 'required|max:100',
            'take_home_pay' => 'required|array',
            'take_home_pay.*' => 'required|digits_between:1,10',
            'job_description' => 'required|array',
            'job_description.*' => 'required',
            'reason_for_resignation' => 'required|array',
            'reason_for_resignation.*' => 'required', // work experience end

            'language_foreign_language' => 'required|array', // Foreign languages start
            'language_foreign_language.*' => 'required|string|max:100',
            'speaking_ability_level_foreign_language' => 'required|array',
            'speaking_ability_level_foreign_language.*' => 'required|string|in:Good,Fair,Poor',
            'writing_ability_level_foreign_language' => 'required|array',
            'writing_ability_level_foreign_language.*' => 'required|string|in:Good,Fair,Poor', // Foreign languages end

            'relationship_id_hospital_connection' => 'nullable|exists:mrelationships,id', // hospital connection table start
            'name_hospital_connection' => 'nullable',
            'department_id_hospital_connection' => 'nullable|exists:mdepartments,id',
            'position_id_hospital_connection' => 'nullable|exists:mpositions,id', // hospital connection table end

            'self_perspective' => 'required', // self perspective table start
            'strengths' => 'required',
            'weaknesses' => 'required',
            'successes' => 'required',
            'failures' => 'required',
            'career_overview' => 'required',
            'future_expectations' => 'required', // self perspective table end

            'physical_condition' => 'required', // additional information table start
            'severe_diseases' => 'required',
            'hospitalizations' => 'required',
            'last_medical_checkup' => 'required', // additional information table end

            'job_vacancy_id' => 'required', // human resources tests table start
            'source_of_info' => 'required',
            'motivation' => 'required',
            'self_assessment' => 'required',
            'desired_position' => 'required',
            'coping_with_department_change' => 'required',
            'previous_job_challenges' => 'required',
            'reason_for_resignation' => 'required',
            'conflict_management' => 'required',
            'stress_management' => 'required',
            'overtime_availability' => 'required',
            'handling_complaints' => 'required',
            'salary_expectation' => 'required',
            'benefits_facilities' => 'required', // human resources tests table end
        ];
    }
}
