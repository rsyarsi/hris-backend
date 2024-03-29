<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CoursesTrainingCandidateRequest;
use App\Services\CoursesTrainingCandidate\CoursesTrainingCandidateServiceInterface;

class CoursesTrainingCandidateController extends Controller
{
    use ResponseAPI;

    private $coursesTrainingCandidateService;

    public function __construct(CoursesTrainingCandidateServiceInterface $coursesTrainingCandidateService)
    {
        $this->middleware('auth:api');
        $this->coursesTrainingCandidateService = $coursesTrainingCandidateService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $coursesTrainingCandidates = $this->coursesTrainingCandidateService->index($perPage, $search);
            return $this->success('Courses Training Candidate retrieved successfully', $coursesTrainingCandidates);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(CoursesTrainingCandidateRequest $request)
    {
        try {
            $data = $request->validated();
            $coursesTrainingCandidate = $this->coursesTrainingCandidateService->store($data);
            return $this->success('Courses Training Candidate created successfully', $coursesTrainingCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $coursesTrainingCandidate = $this->coursesTrainingCandidateService->show($id);
            if (!$coursesTrainingCandidate) {
                return $this->error('Courses Training Candidate not found', 404);
            }
            return $this->success('Courses Training Candidate retrieved successfully', $coursesTrainingCandidate);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(CoursesTrainingCandidateRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $coursesTrainingCandidate = $this->coursesTrainingCandidateService->update($id, $data);
            if (!$coursesTrainingCandidate) {
                return $this->error('Courses Training Candidate not found', 404);
            }
            return $this->success('Courses Training Candidate updated successfully', $coursesTrainingCandidate, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $coursesTrainingCandidate = $this->coursesTrainingCandidateService->destroy($id);
            if (!$coursesTrainingCandidate) {
                return $this->error('Courses Training Candidate not found', 404);
            }
            return $this->success('Courses Training Candidate deleted successfully, id : '.$coursesTrainingCandidate->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
