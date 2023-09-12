<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveTypeRequest;
use App\Services\LeaveType\LeaveTypeServiceInterface;

class LeaveTypeController extends Controller
{
    use ResponseAPI;

    private $leavetypeService;

    public function __construct(LeaveTypeServiceInterface $leavetypeService)
    {
        $this->middleware('auth:api');
        $this->leavetypeService = $leavetypeService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $leavetypes = $this->leavetypeService->index($perPage, $search);
            return $this->success('Leave Types retrieved successfully', $leavetypes);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(LeaveTypeRequest $request)
    {
        try {
            $data = $request->validated();
            $leavetype = $this->leavetypeService->store($data);
            return $this->success('Leave Type created successfully', $leavetype, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $leavetype = $this->leavetypeService->show($id);
            if (!$leavetype) {
                return $this->error('LeaveType not found', 404);
            }
            return $this->success('Leave Type retrieved successfully', $leavetype);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(LeaveTypeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $leavetype = $this->leavetypeService->update($id, $data);
            if (!$leavetype) {
                return $this->error('Leave Type not found', 404);
            }
            return $this->success('Leave Type updated successfully', $leavetype, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $leavetype = $this->leavetypeService->destroy($id);
            if (!$leavetype) {
                return $this->error('Leave Type not found', 404);
            }
            return $this->success('Leave Type deleted successfully, id : '.$leavetype->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
