<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmergencyContactCandidateRequest;
use App\Services\EmergencyContactCandidate\EmergencyContactCandidateServiceInterface;

class EmergencyContactCandidateController extends Controller
{
    use ResponseAPI;

    private $emergencyContactCandidateService;

    public function __construct(EmergencyContactCandidateServiceInterface $emergencyContactCandidateService)
    {
        $this->middleware('api_or_candidate_auth');
        $this->emergencyContactCandidateService = $emergencyContactCandidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $emergencycontactcandidates = $this->emergencyContactCandidateService->index($perPage, $search);
            return $this->success('Emergency Contact Candidate retrieved successfully', $emergencycontactcandidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EmergencyContactCandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $emergencycontactcandidate = $this->emergencyContactCandidateService->store($data);
            return $this->success('Emergency Contact Candidate created successfully', $emergencycontactcandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $emergencycontactcandidate = $this->emergencyContactCandidateService->show($id);
            if (!$emergencycontactcandidate) {
                return $this->error('Emergency Contact Candidate not found', 404);
            }
            return $this->success('Emergency Contact Candidate retrieved successfully', $emergencycontactcandidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EmergencyContactCandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $emergencycontactcandidate = $this->emergencyContactCandidateService->update($id, $data);
            if (!$emergencycontactcandidate) {
                return $this->error('Emergency Contact Candidate not found', 404);
            }
            return $this->success('Emergency Contact Candidate updated successfully', $emergencycontactcandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $emergencycontactcandidate = $this->emergencyContactCandidateService->destroy($id);
            if (!$emergencycontactcandidate) {
                return $this->error('Emergency Contact Candidate not found', 404);
            }
            return $this->success('Emergency Contact Candidate deleted successfully, id : ' . $emergencycontactcandidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
