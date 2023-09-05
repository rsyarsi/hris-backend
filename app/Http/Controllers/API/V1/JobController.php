<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Requests\JobRequest;
use App\Http\Controllers\Controller;
use App\Services\Job\JobServiceInterface;

class JobController extends Controller
{
    use ResponseAPI;

    private $jobService;

    public function __construct(JobServiceInterface $jobService)
    {
        $this->middleware('auth:api');
        $this->jobService = $jobService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $jobs = $this->jobService->index($perPage, $search);
            return $this->success('Jobs retrieved successfully', $jobs);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(JobRequest $request)
    {
        try {
            $data = $request->validated();
            $job = $this->jobService->store($data);
            return $this->success('Job created successfully', $job, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $job = $this->jobService->show($id);
            if (!$job) {
                return $this->error('Job not found', 404);
            }
            return $this->success('Job retrieved successfully', $job);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(JobRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $job = $this->jobService->update($id, $data);
            if (!$job) {
                return $this->error('Job not found', 404);
            }
            return $this->success('Job updated successfully', $job, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $job = $this->jobService->destroy($id);
            if (!$job) {
                return $this->error('Job not found', 404);
            }
            return $this->success('Job deleted successfully, id : '.$job->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
