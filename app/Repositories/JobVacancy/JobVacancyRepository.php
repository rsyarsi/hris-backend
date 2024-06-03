<?php

namespace App\Repositories\JobVacancy;

use App\Models\JobVacancy;
use Illuminate\Support\Facades\DB;
use App\Services\Candidate\CandidateServiceInterface;
use App\Repositories\JobVacancy\JobVacancyRepositoryInterface;

class JobVacancyRepository implements JobVacancyRepositoryInterface
{
    private $model;
    private $candidateService;

    public function __construct(JobVacancy $model)
    {
        $this->model = $model;
    }

    private function getCandidateService()
    {
        if (!$this->candidateService) {
            $this->candidateService = app(CandidateServiceInterface::class);
        }
        return $this->candidateService;
    }

    public function index($perPage, $search = null, $startDate = null, $endDate = null, $status = null)
    {
        $query = $this->model
            ->with([
                'userCreated:id,name,email',
                'education:id,name',
            ])
            ->select();
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhere('position', 'like', "%$search%");
            });
        }
        if ($status !== null) {
            $query->where('status', $status);
        }
        if ($startDate !== null) {
            $query->where('start_date', '>=', $startDate);
        }
        if ($endDate !== null) {
            $query->where('end_date', '<=', $endDate);
        }
        return $query->orderBy('start_date', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $jobvacancy = $this->model
            ->with([
                'userCreated:id,name,email',
                'education:id,name',
            ])
            ->where('id', $id)
            ->first();
        return $jobvacancy ? $jobvacancy : $jobvacancy = null;
    }

    public function update($id, $data)
    {
        $jobvacancy = $this->model->find($id);
        if ($jobvacancy) {
            $jobvacancy->update($data);
            return $jobvacancy;
        }
        return null;
    }

    public function destroy($id)
    {
        $jobvacancy = $this->model->find($id);
        if ($jobvacancy) {
            $jobvacancy->delete();
            return $jobvacancy;
        }
        return null;
    }

    public function indexPublic()
    {
        return DB::table('job_vacancies')
            ->join('meducations', 'job_vacancies.education_id', '=', 'meducations.id')
            ->select(
                'job_vacancies.id',
                'job_vacancies.title',
                'job_vacancies.position',
                'job_vacancies.description',
                'job_vacancies.start_date',
                'job_vacancies.end_date',
                'job_vacancies.min_age',
                'job_vacancies.max_age',
                'job_vacancies.experience',
                'job_vacancies.note',
                'meducations.name as education_name'
            )
            ->where('job_vacancies.status', 1)
            ->get();
    }

    public function applyJob(array $data)
    {
        return $data;
        // Candidate
        $candidate = $this->getCandidateService()->store($data);
        $this->getCandidateService()->uploadCv($candidate->id, $data);
        $this->getCandidateService()->uploadPhoto($candidate->id, $data);

        // Family Member
    }

    public function maritalStatus()
    {
        return DB::table('mmaritalstatuses')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function religion()
    {
        return DB::table('mreligions')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function ethnic()
    {
        return DB::table('methnics')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function relationship()
    {
        return DB::table('mrelationships')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function education()
    {
        return DB::table('meducations')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function job()
    {
        return DB::table('mjobs')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();
    }

    public function position()
    {
        return DB::table('mpositions')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function department()
    {
        return DB::table('mdepartments')
            ->select('id', 'name')
            ->where('active', 1)
            ->orderBy('name', 'ASC')
            ->get();
    }
}
