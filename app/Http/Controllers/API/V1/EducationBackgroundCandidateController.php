<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EducationBackgroundCandidateRequest;
use App\Services\EducationBackgroundCandidate\EducationBackgroundCandidateServiceInterface;

class EducationBackgroundCandidateController extends Controller
{
    use ResponseAPI;

    private $educationBackgroundCandidateService;

    public function __construct(EducationBackgroundCandidateServiceInterface $educationBackgroundCandidateService)
    {
        $this->middleware('api_or_candidate_auth');
        $this->educationBackgroundCandidateService = $educationBackgroundCandidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $educationBackgroundCandidates = $this->educationBackgroundCandidateService->index($perPage, $search);
            return $this->success('Education Background Candidate retrieved successfully', $educationBackgroundCandidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EducationBackgroundCandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $educationBackgroundCandidate = $this->educationBackgroundCandidateService->store($data);
            return $this->success('Education Background Candidate created successfully', $educationBackgroundCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $educationBackgroundCandidate = $this->educationBackgroundCandidateService->show($id);
            if (!$educationBackgroundCandidate) {
                return $this->error('Education Background Candidate not found', 404);
            }
            return $this->success('Education Background Candidate retrieved successfully', $educationBackgroundCandidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EducationBackgroundCandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $educationBackgroundCandidate = $this->educationBackgroundCandidateService->update($id, $data);
            if (!$educationBackgroundCandidate) {
                return $this->error('Education Background Candidate not found', 404);
            }
            return $this->success('Education Background Candidate updated successfully', $educationBackgroundCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $educationBackgroundCandidate = $this->educationBackgroundCandidateService->destroy($id);
            if (!$educationBackgroundCandidate) {
                return $this->error('Education Background Candidate not found', 404);
            }
            return $this->success('Education Background Candidate deleted successfully, id : ' . $educationBackgroundCandidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
