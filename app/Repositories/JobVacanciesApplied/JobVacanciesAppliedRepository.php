<?php

namespace App\Repositories\JobVacanciesApplied;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\{JobVacanciesApplied};
use App\Services\Employee\EmployeeServiceInterface;
use App\Repositories\JobVacanciesApplied\JobVacanciesAppliedRepositoryInterface;

class JobVacanciesAppliedRepository implements JobVacanciesAppliedRepositoryInterface
{
    private $model;
    private $employeeService;

    public function __construct(JobVacanciesApplied $model, EmployeeServiceInterface $employeeService)
    {
        $this->model = $model;
        $this->employeeService = $employeeService;
    }

    public function index($perPage, $search = null, $status = null)
    {
        $query = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
                'jobVacancy',
                'jobVacancy.education:id,name',
                'jobInterviewForm',
            ]);

        if ($search !== null) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('candidate_id', $search)
                    ->orWhereHas('candidate', function ($candidateQuery) use ($search) {
                        $candidateQuery->where('first_name', 'ILIKE', "%{$search}%")
                            ->orWhere('middle_name', 'ILIKE', "%{$search}%")
                            ->orWhere('last_name', 'ILIKE', "%{$search}%");
                    })
                    ->orWhereHas('jobVacancy', function ($jobVacancyQuery) use ($search) {
                        $jobVacancyQuery->where('title', 'ILIKE', "%{$search}%")
                            ->orWhere('position', 'ILIKE', "%{$search}%");
                    });
            });
        }
        if ($status) {
            $query->whereRaw('LOWER(status) LIKE ?', ["%" . strtolower($status) . "%"]);
        }
        return $query->orderBy('created_at', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $jobVacanciesApplied = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
                'jobVacancy',
                'jobVacancy.education:id,name',
                'jobInterviewForm',
            ])
            ->where('id', $id)
            ->first();
        return $jobVacanciesApplied ? $jobVacanciesApplied : $jobVacanciesApplied = null;
    }

    public function update($id, $data)
    {
        DB::beginTransaction();
        try {
            $jobVacanciesApplied = $this->model->with('candidate')->find($id);
            $jobVacanciesApplied->update($data);
            $message = 'Data Updated Successfully!';
            $error = false;
            $code = 201;
            $fullName = (string)$jobVacanciesApplied->candidate->first_name . ' ' . $jobVacanciesApplied->candidate->middle_name . ' ' . $jobVacanciesApplied->candidate->last_name;
            if (Str::upper($jobVacanciesApplied->status) == "DITERIMA") { // IF CANDIDATE DITERIMA
                $existEmployee = $this->employeeService->checkNameEmail($fullName, $jobVacanciesApplied->candidate->email);
                if (!$existEmployee) {
                    $dataEmployee = [
                        'name' => $fullName,
                        'sex_id' => $jobVacanciesApplied->candidate->sex_id,
                        'legal_identity_type_id' => $jobVacanciesApplied->candidate->legal_identity_type_id,
                        'legal_identity_number' => $jobVacanciesApplied->candidate->legal_identity_number,
                        'legal_address' => $jobVacanciesApplied->candidate->legal_address,
                        'current_address' => $jobVacanciesApplied->candidate->current_address,
                        'legal_home_phone_number' => $jobVacanciesApplied->candidate->home_phone_number,
                        'current_home_phone_number' => $jobVacanciesApplied->candidate->home_phone_number,
                        'phone_number' => $jobVacanciesApplied->candidate->phone_number,
                        'email' => $jobVacanciesApplied->candidate->email,
                        'birth_place' => $jobVacanciesApplied->candidate->birth_place,
                        'birth_date' => $jobVacanciesApplied->candidate->birth_date,
                        'marital_status_id' => $jobVacanciesApplied->candidate->marital_status_id,
                        'religion_id' => $jobVacanciesApplied->candidate->religion_id,
                        'tax_identify_number' => $jobVacanciesApplied->candidate->tax_identify_number,
                    ];
                    $this->employeeService->store($dataEmployee);
                    $message = 'Data Updated Successfully & Created data employee!';
                }
            }
            DB::commit();
            return [
                'message' => $message,
                'error' => $error,
                'code' => $code,
                'data' => $jobVacanciesApplied
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'message' => $e->getMessage(),
                'error' => true,
                'code' => 422,
                'data' => null
            ];
        }
    }

    public function destroy($id)
    {
        $jobVacanciesApplied = $this->model->find($id);
        if ($jobVacanciesApplied) {
            $jobVacanciesApplied->delete();
            return $jobVacanciesApplied;
        }
        return null;
    }
}
