<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProvinceRequest;
use App\Services\Province\ProvinceServiceInterface;

class ProvinceController extends Controller
{
    use ResponseAPI;

    private $provinceService;

    public function __construct(ProvinceServiceInterface $provinceService)
    {
        $this->middleware('auth:api');
        $this->provinceService = $provinceService;
    }

    public function index()
    {
        try {
            $provinces = $this->provinceService->index();
            return $this->success('Provinces retrieved successfully', $provinces);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(ProvinceRequest $request)
    {
        try {
            $data = $request->validated();
            $province = $this->provinceService->store($data);
            return $this->success('Province created successfully', $province, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $province = $this->provinceService->show($id);
            if (!$province) {
                return $this->error('Province not found', 404);
            }
            return $this->success('Province retrieved successfully', $province);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(ProvinceRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $province = $this->provinceService->update($id, $data);
            if (!$province) {
                return $this->error('Province not found', 404);
            }
            return $this->success('Province updated successfully', $province, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $province = $this->provinceService->destroy($id);
            if (!$province) {
                return $this->error('Province not found', 404);
            }
            return $this->success('Province deleted successfully, id : '.$province->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
