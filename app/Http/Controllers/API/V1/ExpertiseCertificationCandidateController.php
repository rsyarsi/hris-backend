<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpertiseCertificationCandidateRequest;
use App\Services\ExpertiseCertificationCandidate\ExpertiseCertificationCandidateServiceInterface;

class ExpertiseCertificationCandidateController extends Controller
{
    use ResponseAPI;

    private $expertiseCertificationCandidateService;

    public function __construct(ExpertiseCertificationCandidateServiceInterface $expertiseCertificationCandidateService)
    {
        $this->middleware('api_or_candidate_auth');
        $this->expertiseCertificationCandidateService = $expertiseCertificationCandidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $expertiseCertificationCandidates = $this->expertiseCertificationCandidateService->index($perPage, $search);
            return $this->success('Expertise Certification Candidate retrieved successfully', $expertiseCertificationCandidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(ExpertiseCertificationCandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $expertiseCertificationCandidate = $this->expertiseCertificationCandidateService->store($data);
            return $this->success('Expertise Certification Candidate created successfully', $expertiseCertificationCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $expertiseCertificationCandidate = $this->expertiseCertificationCandidateService->show($id);
            if (!$expertiseCertificationCandidate) {
                return $this->error('Expertise Certification Candidate not found', 404);
            }
            return $this->success('Expertise Certification Candidate retrieved successfully', $expertiseCertificationCandidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(ExpertiseCertificationCandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $expertiseCertificationCandidate = $this->expertiseCertificationCandidateService->update($id, $data);
            if (!$expertiseCertificationCandidate) {
                return $this->error('Expertise Certification Candidate not found', 404);
            }
            return $this->success('Expertise Certification Candidate updated successfully', $expertiseCertificationCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $expertiseCertificationCandidate = $this->expertiseCertificationCandidateService->destroy($id);
            if (!$expertiseCertificationCandidate) {
                return $this->error('Expertise Certification Candidate not found', 404);
            }
            return $this->success('Expertise Certification Candidate deleted successfully, id : ' . $expertiseCertificationCandidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
