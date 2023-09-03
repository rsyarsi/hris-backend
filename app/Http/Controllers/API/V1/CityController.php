<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use App\Http\Requests\CityRequest;
use App\Http\Controllers\Controller;
use App\Services\City\CityServiceInterface;

class CityController extends Controller
{
    use ResponseAPI;

    private $cityService;

    public function __construct(CityServiceInterface $cityService)
    {
        $this->middleware('auth:api');
        $this->cityService = $cityService;
    }

    public function index()
    {
        try {
            $citys = $this->cityService->index();
            return $this->success('Citys retrieved successfully', $citys);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(CityRequest $request)
    {
        try {
            $data = $request->validated();
            $city = $this->cityService->store($data);
            return $this->success('City created successfully', $city, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $city = $this->cityService->show($id);
            if (!$city) {
                return $this->error('City not found', 404);
            }
            return $this->success('City retrieved successfully', $city);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(CityRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $city = $this->cityService->update($id, $data);
            if (!$city) {
                return $this->error('City not found', 404);
            }
            return $this->success('City updated successfully', $city, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $city = $this->cityService->destroy($id);
            if (!$city) {
                return $this->error('City not found', 404);
            }
            return $this->success('City deleted successfully, id : '.$city->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
