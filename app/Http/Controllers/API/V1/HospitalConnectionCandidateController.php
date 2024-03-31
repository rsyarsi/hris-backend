<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\HospitalConnectionCandidateRequest;
use App\Services\HospitalConnectionCandidate\HospitalConnectionCandidateServiceInterface;

class HospitalConnectionCandidateController extends Controller
{
    use ResponseAPI;

    private $hospitalConnectionCandidateService;

    public function __construct(HospitalConnectionCandidateServiceInterface $hospitalConnectionCandidateService)
    {
        $this->middleware('api_or_candidate_auth');
        $this->hospitalConnectionCandidateService = $hospitalConnectionCandidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $hospitalConnectionCandidates = $this->hospitalConnectionCandidateService->index($perPage, $search);
            return $this->success('Hospital Connection Candidate retrieved successfully', $hospitalConnectionCandidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(HospitalConnectionCandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $hospitalConnectionCandidate = $this->hospitalConnectionCandidateService->store($data);
            return $this->success('Hospital Connection Candidate created successfully', $hospitalConnectionCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $hospitalConnectionCandidate = $this->hospitalConnectionCandidateService->show($id);
            if (!$hospitalConnectionCandidate) {
                return $this->error('Hospital Connection Candidate not found', 404);
            }
            return $this->success('Hospital Connection Candidate retrieved successfully', $hospitalConnectionCandidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(HospitalConnectionCandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $hospitalConnectionCandidate = $this->hospitalConnectionCandidateService->update($id, $data);
            if (!$hospitalConnectionCandidate) {
                return $this->error('Hospital Connection Candidate not found', 404);
            }
            return $this->success('Hospital Connection Candidate updated successfully', $hospitalConnectionCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $hospitalConnectionCandidate = $this->hospitalConnectionCandidateService->destroy($id);
            if (!$hospitalConnectionCandidate) {
                return $this->error('Hospital Connection Candidate not found', 404);
            }
            return $this->success('Hospital Connection Candidate deleted successfully, id : ' . $hospitalConnectionCandidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
