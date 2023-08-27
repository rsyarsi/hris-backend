<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use App\Http\Controllers\Controller;
use App\Http\Requests\PositionRequest;
use App\Services\Position\PositionServiceInterface;

class PositionController extends Controller
{
    use ResponseAPI;

    private $positionService;

    public function __construct(PositionServiceInterface $positionService)
    {
        $this->middleware('auth:api');
        $this->positionService = $positionService;
    }

    public function index()
    {
        try {
            $positions = $this->positionService->index();
            return $this->success('Positions retrieved successfully', $positions);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(PositionRequest $request)
    {
        try {
            $data = $request->validated();
            $position = $this->positionService->store($data);
            return $this->success('Position created successfully', $position, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $position = $this->positionService->show($id);
            if (!$position) {
                return $this->error('Position not found', 404);
            }
            return $this->success('Position retrieved successfully', $position);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(PositionRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $position = $this->positionService->update($id, $data);
            if (!$position) {
                return $this->error('Position not found', 404);
            }
            return $this->success('Position updated successfully', $position, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $position = $this->positionService->destroy($id);
            if (!$position) {
                return $this->error('Position not found', 404);
            }
            return $this->success('Position deleted successfully, id : '.$position->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
