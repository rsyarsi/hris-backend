<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationExperienceCandidateRequest;
use App\Services\OrganizationExperienceCandidate\OrganizationExperienceCandidateServiceInterface;

class OrganizationExperienceCandidateController extends Controller
{
    use ResponseAPI;

    private $organizationExperienceCandidateService;

    public function __construct(OrganizationExperienceCandidateServiceInterface $organizationExperienceCandidateService)
    {
        $this->middleware('auth:api');
        $this->organizationExperienceCandidateService = $organizationExperienceCandidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $organizationExperienceCandidates = $this->organizationExperienceCandidateService->index($perPage, $search);
            return $this->success('Organization Experience Candidate retrieved successfully', $organizationExperienceCandidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(OrganizationExperienceCandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $organizationExperienceCandidate = $this->organizationExperienceCandidateService->store($data);
            return $this->success('Organization Experience Candidate created successfully', $organizationExperienceCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $organizationExperienceCandidate = $this->organizationExperienceCandidateService->show($id);
            if (!$organizationExperienceCandidate) {
                return $this->error('Organization Experience Candidate not found', 404);
            }
            return $this->success('Organization Experience Candidate retrieved successfully', $organizationExperienceCandidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(OrganizationExperienceCandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $organizationExperienceCandidate = $this->organizationExperienceCandidateService->update($id, $data);
            if (!$organizationExperienceCandidate) {
                return $this->error('Organization Experience Candidate not found', 404);
            }
            return $this->success('Organization Experience Candidate updated successfully', $organizationExperienceCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $organizationExperienceCandidate = $this->organizationExperienceCandidateService->destroy($id);
            if (!$organizationExperienceCandidate) {
                return $this->error('Organization Experience Candidate not found', 404);
            }
            return $this->success('Organization Experience Candidate deleted successfully, id : '.$organizationExperienceCandidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
