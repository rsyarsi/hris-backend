<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use App\Http\Requests\SexRequest;
use App\Http\Controllers\Controller;
use App\Services\Sex\SexServiceInterface;

class SexController extends Controller
{
    use ResponseAPI;

    private $sexService;

    public function __construct(SexServiceInterface $sexService)
    {
        $this->middleware('auth:api');
        $this->sexService = $sexService;
    }

    public function index()
    {
        try {
            $sexs = $this->sexService->index();
            return $this->success('Sexs retrieved successfully', $sexs);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(SexRequest $request)
    {
        try {
            $data = $request->validated();
            $sex = $this->sexService->store($data);
            return $this->success('Sex created successfully', $sex, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $sex = $this->sexService->show($id);
            if (!$sex) {
                return $this->error('Sex not found', 404);
            }
            return $this->success('Sex retrieved successfully', $sex);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(SexRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $sex = $this->sexService->update($id, $data);
            if (!$sex) {
                return $this->error('Sex not found', 404);
            }
            return $this->success('Sex updated successfully', $sex, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $sex = $this->sexService->destroy($id);
            if (!$sex) {
                return $this->error('Sex not found', 404);
            }
            return $this->success('Sex deleted successfully, id : '.$sex->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
