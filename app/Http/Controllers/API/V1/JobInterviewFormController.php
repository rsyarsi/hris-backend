<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobInterviewFormRequest;
use App\Services\JobInterviewForm\JobInterviewFormServiceInterface;

class JobInterviewFormController extends Controller
{
    use ResponseAPI;

    private $jobInterviewFormService;

    public function __construct(JobInterviewFormServiceInterface $jobInterviewFormService)
    {
        $this->middleware('auth:api');
        $this->jobInterviewFormService = $jobInterviewFormService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $period1 = $request->input('period_1');
            $period2 = $request->input('period_2');
            $jobinterviewforms = $this->jobInterviewFormService->index($perPage, $search, $period1, $period2);
            return $this->success('Job Interview Form retrieved successfully', $jobinterviewforms);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(JobInterviewFormRequest $request)
    {
        $data = $request->validated();
        $jobinterviewform = $this->jobInterviewFormService->store($data);
        return $this->success('Job Interview Form created successfully', $jobinterviewform, 201);
        try {
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $jobinterviewform = $this->jobInterviewFormService->show($id);
            if (!$jobinterviewform) {
                return $this->error('Job Interview Form not found', 404);
            }
            return $this->success('Job Interview Form retrieved successfully', $jobinterviewform);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(JobInterviewFormRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $jobinterviewform = $this->jobInterviewFormService->update($id, $data);
            if (!$jobinterviewform) {
                return $this->error('Job Interview Form not found', 404);
            }
            return $this->success('Job Interview Form updated successfully', $jobinterviewform, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $jobinterviewform = $this->jobInterviewFormService->destroy($id);
            if (!$jobinterviewform) {
                return $this->error('Job Interview Form not found', 404);
            }
            return $this->success('Job Interview Form deleted successfully, id : ' . $jobinterviewform->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    function interviewer(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $period1 = $request->input('period_1');
            $period2 = $request->input('period_2');
            $jobinterviewforms = $this->jobInterviewFormService->interviewer($perPage, $search, $period1, $period2);
            return $this->success('Job Interview Form Interviewer retrieved successfully', $jobinterviewforms);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
