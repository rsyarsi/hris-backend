<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaritalStatusRequest;
use App\Services\MaritalStatus\MaritalStatusServiceInterface;

class MaritalStatusController extends Controller
{
    use ResponseAPI;

    private $maritalstatusService;

    public function __construct(MaritalStatusServiceInterface $maritalstatusService)
    {
        $this->middleware('auth:api');
        $this->maritalstatusService = $maritalstatusService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $maritalstatuss = $this->maritalstatusService->index($perPage, $search);
            return $this->success('Marital Status retrieved successfully', $maritalstatuss);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(MaritalStatusRequest $request)
    {
        try {
            $data = $request->validated();
            $maritalstatus = $this->maritalstatusService->store($data);
            return $this->success('Marital Status created successfully', $maritalstatus, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $maritalstatus = $this->maritalstatusService->show($id);
            if (!$maritalstatus) {
                return $this->error('Marital Status not found', 404);
            }
            return $this->success('Marital Status retrieved successfully', $maritalstatus);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(MaritalStatusRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $maritalstatus = $this->maritalstatusService->update($id, $data);
            if (!$maritalstatus) {
                return $this->error('Marital Status not found', 404);
            }
            return $this->success('Marital Status updated successfully', $maritalstatus, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $maritalstatus = $this->maritalstatusService->destroy($id);
            if (!$maritalstatus) {
                return $this->error('Marital Status not found', 404);
            }
            return $this->success('Marital Status deleted successfully, id : '.$maritalstatus->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
