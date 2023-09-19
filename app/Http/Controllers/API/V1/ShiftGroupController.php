<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShiftGroupRequest;
use App\Services\ShiftGroup\ShiftGroupServiceInterface;

class ShiftGroupController extends Controller
{
    use ResponseAPI;

    private $shiftgroupService;

    public function __construct(ShiftGroupServiceInterface $shiftgroupService)
    {
        $this->middleware('auth:api');
        $this->shiftgroupService = $shiftgroupService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $shiftgroups = $this->shiftgroupService->index($perPage, $search);
            return $this->success('Shift Groups retrieved successfully', $shiftgroups);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(ShiftGroupRequest $request)
    {
        try {
            $data = $request->validated();
            $shiftgroup = $this->shiftgroupService->store($data);
            return $this->success('Shift Group created successfully', $shiftgroup, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $shiftgroup = $this->shiftgroupService->show($id);
            if (!$shiftgroup) {
                return $this->error('Shift Group not found', 404);
            }
            return $this->success('Shift Group retrieved successfully', $shiftgroup);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(ShiftGroupRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $shiftgroup = $this->shiftgroupService->update($id, $data);
            if (!$shiftgroup) {
                return $this->error('Shift Group not found', 404);
            }
            return $this->success('Shift Group updated successfully', $shiftgroup, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $shiftgroup = $this->shiftgroupService->destroy($id);
            if (!$shiftgroup) {
                return $this->error('Shift Group not found', 404);
            }
            return $this->success('Shift Group deleted successfully, id : '.$shiftgroup->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
