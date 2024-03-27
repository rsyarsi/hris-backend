<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CandidateRequest;
use App\Services\Candidate\CandidateServiceInterface;

class CandidateController extends Controller
{
    use ResponseAPI;

    private $candidateService;

    public function __construct(CandidateServiceInterface $candidateService)
    {
        $this->middleware('auth:api');
        $this->candidateService = $candidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $candidates = $this->candidateService->index($perPage, $search);
            return $this->success('Candidates retrieved successfully', $candidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(CandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $candidate = $this->candidateService->store($data);
            return $this->success('Candidate created successfully', $candidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $candidate = $this->candidateService->show($id);
            if (!$candidate) {
                return $this->error('Candidate not found', 404);
            }
            return $this->success('Candidate retrieved successfully', $candidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(CandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $candidate = $this->candidateService->update($id, $data);
            if (!$candidate) {
                return $this->error('Candidate not found', 404);
            }
            return $this->success('Candidate updated successfully', $candidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $candidate = $this->candidateService->destroy($id);
            if (!$candidate) {
                return $this->error('Candidate not found', 404);
            }
            return $this->success('Candidate deleted successfully, id : '.$candidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
