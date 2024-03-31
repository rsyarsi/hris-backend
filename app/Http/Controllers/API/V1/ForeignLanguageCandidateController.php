<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForeignLanguageCandidateRequest;
use App\Services\ForeignLanguageCandidate\ForeignLanguageCandidateServiceInterface;

class ForeignLanguageCandidateController extends Controller
{
    use ResponseAPI;

    private $foreignLanguageCandidateService;

    public function __construct(ForeignLanguageCandidateServiceInterface $foreignLanguageCandidateService)
    {
        $this->middleware('api_or_candidate_auth');
        $this->foreignLanguageCandidateService = $foreignLanguageCandidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $foreignLanguageCandidates = $this->foreignLanguageCandidateService->index($perPage, $search);
            return $this->success('Foreign Language Candidate retrieved successfully', $foreignLanguageCandidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(ForeignLanguageCandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $foreignLanguageCandidate = $this->foreignLanguageCandidateService->store($data);
            return $this->success('Foreign Language Candidate created successfully', $foreignLanguageCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $foreignLanguageCandidate = $this->foreignLanguageCandidateService->show($id);
            if (!$foreignLanguageCandidate) {
                return $this->error('Foreign Language Candidate not found', 404);
            }
            return $this->success('Foreign Language Candidate retrieved successfully', $foreignLanguageCandidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(ForeignLanguageCandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $foreignLanguageCandidate = $this->foreignLanguageCandidateService->update($id, $data);
            if (!$foreignLanguageCandidate) {
                return $this->error('Foreign Language Candidate not found', 404);
            }
            return $this->success('Foreign Language Candidate updated successfully', $foreignLanguageCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $foreignLanguageCandidate = $this->foreignLanguageCandidateService->destroy($id);
            if (!$foreignLanguageCandidate) {
                return $this->error('Foreign Language Candidate not found', 404);
            }
            return $this->success('Foreign Language Candidate deleted successfully, id : ' . $foreignLanguageCandidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
