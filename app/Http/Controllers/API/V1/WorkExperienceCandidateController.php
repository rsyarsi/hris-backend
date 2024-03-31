<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkExperienceCandidateRequest;
use App\Services\WorkExperienceCandidate\WorkExperienceCandidateServiceInterface;

class WorkExperienceCandidateController extends Controller
{
    use ResponseAPI;

    private $workExperienceCandidateService;

    public function __construct(WorkExperienceCandidateServiceInterface $workExperienceCandidateService)
    {
        $this->middleware('api_or_candidate_auth');
        $this->workExperienceCandidateService = $workExperienceCandidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $workExperienceCandidates = $this->workExperienceCandidateService->index($perPage, $search);
            return $this->success('Work Experience Candidate retrieved successfully', $workExperienceCandidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(WorkExperienceCandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $workExperienceCandidate = $this->workExperienceCandidateService->store($data);
            return $this->success('Work Experience Candidate created successfully', $workExperienceCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $workExperienceCandidate = $this->workExperienceCandidateService->show($id);
            if (!$workExperienceCandidate) {
                return $this->error('Work Experience Candidate not found', 404);
            }
            return $this->success('Work Experience Candidate retrieved successfully', $workExperienceCandidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(WorkExperienceCandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $workExperienceCandidate = $this->workExperienceCandidateService->update($id, $data);
            if (!$workExperienceCandidate) {
                return $this->error('Work Experience Candidate not found', 404);
            }
            return $this->success('Work Experience Candidate updated successfully', $workExperienceCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $workExperienceCandidate = $this->workExperienceCandidateService->destroy($id);
            if (!$workExperienceCandidate) {
                return $this->error('Work Experience Candidate not found', 404);
            }
            return $this->success('Work Experience Candidate deleted successfully, id : ' . $workExperienceCandidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
