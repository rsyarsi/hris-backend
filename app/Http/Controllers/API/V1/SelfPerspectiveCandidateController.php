<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SelfPerspectiveCandidateRequest;
use App\Services\SelfPerspectiveCandidate\SelfPerspectiveCandidateServiceInterface;

class SelfPerspectiveCandidateController extends Controller
{
    use ResponseAPI;

    private $selfPerspectiveCandidateService;

    public function __construct(SelfPerspectiveCandidateServiceInterface $selfPerspectiveCandidateService)
    {
        $this->middleware('auth:api');
        $this->selfPerspectiveCandidateService = $selfPerspectiveCandidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $selfPerspectiveCandidates = $this->selfPerspectiveCandidateService->index($perPage, $search);
            return $this->success('Self Perspective Candidate retrieved successfully', $selfPerspectiveCandidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(SelfPerspectiveCandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $selfPerspectiveCandidate = $this->selfPerspectiveCandidateService->store($data);
            return $this->success('Self Perspective Candidate created successfully', $selfPerspectiveCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $selfPerspectiveCandidate = $this->selfPerspectiveCandidateService->show($id);
            if (!$selfPerspectiveCandidate) {
                return $this->error('Self Perspective Candidate not found', 404);
            }
            return $this->success('Self Perspective Candidate retrieved successfully', $selfPerspectiveCandidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(SelfPerspectiveCandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $selfPerspectiveCandidate = $this->selfPerspectiveCandidateService->update($id, $data);
            if (!$selfPerspectiveCandidate) {
                return $this->error('Self Perspective Candidate not found', 404);
            }
            return $this->success('Self Perspective Candidate updated successfully', $selfPerspectiveCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $selfPerspectiveCandidate = $this->selfPerspectiveCandidateService->destroy($id);
            if (!$selfPerspectiveCandidate) {
                return $this->error('Self Perspective Candidate not found', 404);
            }
            return $this->success('Self Perspective Candidate deleted successfully, id : ' . $selfPerspectiveCandidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
