<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FamilyInformationCandidateRequest;
use App\Services\FamilyInformationCandidate\FamilyInformationCandidateServiceInterface;

class FamilyInformationCandidateController extends Controller
{
    use ResponseAPI;

    private $familyInformationCandidateService;

    public function __construct(FamilyInformationCandidateServiceInterface $familyInformationCandidateService)
    {
        $this->middleware('auth:api');
        $this->familyInformationCandidateService = $familyInformationCandidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $familyInformationCandidates = $this->familyInformationCandidateService->index($perPage, $search);
            return $this->success('Family Information Candidate retrieved successfully', $familyInformationCandidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(FamilyInformationCandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $familyInformationCandidate = $this->familyInformationCandidateService->store($data);
            return $this->success('Family Information Candidate created successfully', $familyInformationCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $familyInformationCandidate = $this->familyInformationCandidateService->show($id);
            if (!$familyInformationCandidate) {
                return $this->error('Family Information Candidate not found', 404);
            }
            return $this->success('Family Information Candidate retrieved successfully', $familyInformationCandidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(FamilyInformationCandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $familyInformationCandidate = $this->familyInformationCandidateService->update($id, $data);
            if (!$familyInformationCandidate) {
                return $this->error('Family Information Candidate not found', 404);
            }
            return $this->success('Family Information Candidate updated successfully', $familyInformationCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $familyInformationCandidate = $this->familyInformationCandidateService->destroy($id);
            if (!$familyInformationCandidate) {
                return $this->error('Family Information Candidate not found', 404);
            }
            return $this->success('Family Information Candidate deleted successfully, id : ' . $familyInformationCandidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
