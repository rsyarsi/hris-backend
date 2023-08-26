<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use App\Http\Controllers\Controller;
use App\Http\Requests\EducationRequest;
use App\Services\Education\EducationServiceInterface;

class EducationController extends Controller
{
    use ResponseAPI;

    private $educationService;

    public function __construct(EducationServiceInterface $educationService)
    {
        $this->middleware('auth:api');
        $this->educationService = $educationService;
    }

    public function index()
    {
        try {
            $educations = $this->educationService->index();
            return $this->success('Educations retrieved successfully', $educations);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(EducationRequest $request)
    {
        try {
            $data = $request->validated();
            $education = $this->educationService->store($data);
            return $this->success('Education created successfully', $education, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $education = $this->educationService->show($id);
            if (!$education) {
                return $this->error('Education not found', 404);
            }
            return $this->success('Education retrieved successfully', $education);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(EducationRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $education = $this->educationService->update($id, $data);
            if (!$education) {
                return $this->error('Education not found', 404);
            }
            return $this->success('Education updated successfully', $education, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $education = $this->educationService->destroy($id);
            if (!$education) {
                return $this->error('Education not found', 404);
            }
            return $this->success('Education deleted successfully, id : '.$education->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
