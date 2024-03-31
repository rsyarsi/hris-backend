<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdditionalInformationCandidateRequest;
use App\Services\AdditionalInformationCandidate\AdditionalInformationCandidateServiceInterface;

class AdditionalInformationCandidateController extends Controller
{
    use ResponseAPI;

    private $additionalInformationCandidateService;

    public function __construct(AdditionalInformationCandidateServiceInterface $additionalInformationCandidateService)
    {
        $this->middleware('api_or_candidate_auth');
        $this->additionalInformationCandidateService = $additionalInformationCandidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $additionalInformationCandidates = $this->additionalInformationCandidateService->index($perPage, $search);
            return $this->success('Additional Information Candidate retrieved successfully', $additionalInformationCandidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(AdditionalInformationCandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $additionalInformationCandidate = $this->additionalInformationCandidateService->store($data);
            return $this->success('Additional Information Candidate created successfully', $additionalInformationCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $additionalInformationCandidate = $this->additionalInformationCandidateService->show($id);
            if (!$additionalInformationCandidate) {
                return $this->error('Additional Information Candidate not found', 404);
            }
            return $this->success('Additional Information Candidate retrieved successfully', $additionalInformationCandidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(AdditionalInformationCandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $additionalInformationCandidate = $this->additionalInformationCandidateService->update($id, $data);
            if (!$additionalInformationCandidate) {
                return $this->error('Additional Information Candidate not found', 404);
            }
            return $this->success('Additional Information Candidate updated successfully', $additionalInformationCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $additionalInformationCandidate = $this->additionalInformationCandidateService->destroy($id);
            if (!$additionalInformationCandidate) {
                return $this->error('Additional Information Candidate not found', 404);
            }
            return $this->success('Additional Information Candidate deleted successfully, id : ' . $additionalInformationCandidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
