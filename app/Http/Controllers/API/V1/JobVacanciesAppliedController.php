<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\{JobVacanciesAppliedRequest, SendEmailInvitationInterviewRequest};
use App\Services\JobVacanciesApplied\JobVacanciesAppliedServiceInterface;

class JobVacanciesAppliedController extends Controller
{
    use ResponseAPI;

    private $jobVacanciesAppliedService;

    public function __construct(JobVacanciesAppliedServiceInterface $jobVacanciesAppliedService)
    {
        $this->middleware('api_or_candidate_auth');
        $this->jobVacanciesAppliedService = $jobVacanciesAppliedService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $status = $request->input('status');
            $jobVacanciesApplied = $this->jobVacanciesAppliedService->index($perPage, $search, $status);
            return $this->success('Job Vacancies Applied retrieved successfully', $jobVacanciesApplied);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(JobVacanciesAppliedRequest $request)
    {
        try {
            $data = $request->validated();
            $jobVacanciesApplied = $this->jobVacanciesAppliedService->store($data);
            return $this->success('Job Vacancies Applied created successfully', $jobVacanciesApplied, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $jobVacanciesApplied = $this->jobVacanciesAppliedService->show($id);
            if (!$jobVacanciesApplied) {
                return $this->error('Job Vacancies Applied not found', 404);
            }
            return $this->success('Job Vacancies Applied retrieved successfully', $jobVacanciesApplied);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(JobVacanciesAppliedRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $jobVacanciesApplied = $this->jobVacanciesAppliedService->update($id, $data);
            if (!$jobVacanciesApplied) {
                return $this->error('Job Vacancies Applied not found', 404);
            }
            return $this->success('Job Vacancies Applied updated successfully', $jobVacanciesApplied, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $jobVacanciesApplied = $this->jobVacanciesAppliedService->destroy($id);
            if (!$jobVacanciesApplied) {
                return $this->error('Job Vacancies Applied not found', 404);
            }
            return $this->success('Job Vacancies Applied deleted successfully, id : ' . $jobVacanciesApplied->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function sendEmailInterview(SendEmailInvitationInterviewRequest $request)
    {
        try {
            $data = $request->validated();
            $jobVacanciesApplied = $this->jobVacanciesAppliedService->sendEmailInterview($data);
            // return $jobVacanciesApplied;
            return response()->json([
                'message' => $jobVacanciesApplied['message'],
                'error' => $jobVacanciesApplied['error'],
                'code' => $jobVacanciesApplied['code'],
                'data' => $jobVacanciesApplied['data']
            ], $jobVacanciesApplied['code']);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
