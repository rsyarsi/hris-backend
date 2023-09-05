<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Requests\UnitRequest;
use App\Http\Controllers\Controller;
use App\Services\Unit\UnitServiceInterface;

class UnitController extends Controller
{
    use ResponseAPI;

    private $unitService;

    public function __construct(UnitServiceInterface $unitService)
    {
        $this->middleware('auth:api');
        $this->unitService = $unitService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $units = $this->unitService->index($perPage, $search);
            return $this->success('Units retrieved successfully', $units);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(UnitRequest $request)
    {
        try {
            $data = $request->validated();
            $unit = $this->unitService->store($data);
            return $this->success('Unit created successfully', $unit, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $unit = $this->unitService->show($id);
            if (!$unit) {
                return $this->error('Unit not found', 404);
            }
            return $this->success('Unit retrieved successfully', $unit);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(UnitRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $unit = $this->unitService->update($id, $data);
            if (!$unit) {
                return $this->error('Unit not found', 404);
            }
            return $this->success('Unit updated successfully', $unit, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $unit = $this->unitService->destroy($id);
            if (!$unit) {
                return $this->error('Unit not found', 404);
            }
            return $this->success('Unit deleted successfully, id : '.$unit->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
