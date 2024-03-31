<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FamilyMemberCandidateRequest;
use App\Services\FamilyMemberCandidate\FamilyMemberCandidateServiceInterface;

class FamilyMemberCandidateController extends Controller
{
    use ResponseAPI;

    private $familyMemberCandidateService;

    public function __construct(FamilyMemberCandidateServiceInterface $familyMemberCandidateService)
    {
        $this->middleware('api_or_candidate_auth');
        $this->familyMemberCandidateService = $familyMemberCandidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $familyMemberCandidates = $this->familyMemberCandidateService->index($perPage, $search);
            return $this->success('Family Member Candidate retrieved successfully', $familyMemberCandidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(FamilyMemberCandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $familyMemberCandidate = $this->familyMemberCandidateService->store($data);
            return $this->success('Family Member Candidate created successfully', $familyMemberCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $familyMemberCandidate = $this->familyMemberCandidateService->show($id);
            if (!$familyMemberCandidate) {
                return $this->error('Family Member Candidate not found', 404);
            }
            return $this->success('Family Member Candidate retrieved successfully', $familyMemberCandidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(FamilyMemberCandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $familyMemberCandidate = $this->familyMemberCandidateService->update($id, $data);
            if (!$familyMemberCandidate) {
                return $this->error('Family Member Candidate not found', 404);
            }
            return $this->success('Family Member Candidate updated successfully', $familyMemberCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $familyMemberCandidate = $this->familyMemberCandidateService->destroy($id);
            if (!$familyMemberCandidate) {
                return $this->error('Family Member Candidate not found', 404);
            }
            return $this->success('Family Member Candidate deleted successfully, id : ' . $familyMemberCandidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
