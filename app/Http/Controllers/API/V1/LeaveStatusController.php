<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveStatusRequest;
use App\Services\LeaveStatus\LeaveStatusServiceInterface;

class LeaveStatusController extends Controller
{
    use ResponseAPI;

    private $leavestatusService;

    public function __construct(LeaveStatusServiceInterface $leavestatusService)
    {
        $this->middleware('auth:api');
        $this->leavestatusService = $leavestatusService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $leavestatuss = $this->leavestatusService->index($perPage, $search);
            return $this->success('Leave Statuses retrieved successfully', $leavestatuss);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(LeaveStatusRequest $request)
    {
        try {
            $data = $request->validated();
            $leavestatus = $this->leavestatusService->store($data);
            return $this->success('Leave Status created successfully', $leavestatus, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $leavestatus = $this->leavestatusService->show($id);
            if (!$leavestatus) {
                return $this->error('Leave Status not found', 404);
            }
            return $this->success('Leave Status retrieved successfully', $leavestatus);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(LeaveStatusRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $leavestatus = $this->leavestatusService->update($id, $data);
            if (!$leavestatus) {
                return $this->error('Leave Status not found', 404);
            }
            return $this->success('Leave Status updated successfully', $leavestatus, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $leavestatus = $this->leavestatusService->destroy($id);
            if (!$leavestatus) {
                return $this->error('Leave Status not found', 404);
            }
            return $this->success('Leave Status deleted successfully, id : '.$leavestatus->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
