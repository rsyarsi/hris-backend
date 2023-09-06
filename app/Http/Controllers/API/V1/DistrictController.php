<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DistrictRequest;
use App\Services\District\DistrictServiceInterface;

class DistrictController extends Controller
{
    use ResponseAPI;

    private $districtService;

    public function __construct(DistrictServiceInterface $districtService)
    {
        $this->middleware('auth:api');
        $this->districtService = $districtService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $districts = $this->districtService->index($perPage, $search);
            return $this->success('Districts retrieved successfully', $districts);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(DistrictRequest $request)
    {
        try {
            $data = $request->validated();
            $district = $this->districtService->store($data);
            return $this->success('District created successfully', $district, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $district = $this->districtService->show($id);
            if (!$district) {
                return $this->error('District not found', 404);
            }
            return $this->success('District retrieved successfully', $district);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(DistrictRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $district = $this->districtService->update($id, $data);
            if (!$district) {
                return $this->error('District not found', 404);
            }
            return $this->success('District updated successfully', $district, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $district = $this->districtService->destroy($id);
            if (!$district) {
                return $this->error('District not found', 404);
            }
            return $this->success('District deleted successfully, id : '.$district->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
