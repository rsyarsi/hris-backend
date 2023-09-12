<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveApprovalRequest;
use App\Services\LeaveApproval\LeaveApprovalServiceInterface;

class LeaveApprovalController extends Controller
{
    use ResponseAPI;

    private $leaveapprovalService;

    public function __construct(LeaveApprovalServiceInterface $leaveapprovalService)
    {
        $this->middleware('auth:api');
        $this->leaveapprovalService = $leaveapprovalService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $leaveapprovals = $this->leaveapprovalService->index($perPage, $search);
            return $this->success('Leave Approval retrieved successfully', $leaveapprovals);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(LeaveApprovalRequest $request)
    {
        try {
            $data = $request->validated();
            $leaveapproval = $this->leaveapprovalService->store($data);
            return $this->success('Leave Approval created successfully', $leaveapproval, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $leaveapproval = $this->leaveapprovalService->show($id);
            if (!$leaveapproval) {
                return $this->error('Leave Approval not found', 404);
            }
            return $this->success('Leave Approval retrieved successfully', $leaveapproval);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(LeaveApprovalRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $leaveapproval = $this->leaveapprovalService->update($id, $data);
            if (!$leaveapproval) {
                return $this->error('Leave Approval not found', 404);
            }
            return $this->success('Leave Approval updated successfully', $leaveapproval, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $leaveapproval = $this->leaveapprovalService->destroy($id);
            if (!$leaveapproval) {
                return $this->error('Leave Approval not found', 404);
            }
            return $this->success('Leave Approval deleted successfully, id : '.$leaveapproval->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
