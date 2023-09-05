<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StatusEmploymentRequest;
use App\Services\StatusEmployment\StatusEmploymentServiceInterface;

class StatusEmploymentController extends Controller
{
    use ResponseAPI;

    private $statusemploymentService;

    public function __construct(StatusEmploymentServiceInterface $statusemploymentService)
    {
        $this->middleware('auth:api');
        $this->statusemploymentService = $statusemploymentService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $statusemployments = $this->statusemploymentService->index($perPage, $search);
            return $this->success('Status Employments retrieved successfully', $statusemployments);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(StatusEmploymentRequest $request)
    {
        try {
            $data = $request->validated();
            $statusemployment = $this->statusemploymentService->store($data);
            return $this->success('Status Employment created successfully', $statusemployment, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $statusemployment = $this->statusemploymentService->show($id);
            if (!$statusemployment) {
                return $this->error('Status Employment not found', 404);
            }
            return $this->success('Status Employment retrieved successfully', $statusemployment);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(StatusEmploymentRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $statusemployment = $this->statusemploymentService->update($id, $data);
            if (!$statusemployment) {
                return $this->error('Status Employment not found', 404);
            }
            return $this->success('Status Employment updated successfully', $statusemployment, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $statusemployment = $this->statusemploymentService->destroy($id);
            if (!$statusemployment) {
                return $this->error('Status Employment not found', 404);
            }
            return $this->success('Status Employment deleted successfully, id : '.$statusemployment->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
