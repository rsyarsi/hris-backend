<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveHistoryRequest;
use App\Services\LeaveHistory\LeaveHistoryServiceInterface;

class LeaveHistoryController extends Controller
{
    use ResponseAPI;

    private $leavehistoryService;

    public function __construct(LeaveHistoryServiceInterface $leavehistoryService)
    {
        $this->middleware('auth:api');
        $this->leavehistoryService = $leavehistoryService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $leavehistorys = $this->leavehistoryService->index($perPage, $search);
            return $this->success('Leave History retrieved successfully', $leavehistorys);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(LeaveHistoryRequest $request)
    {
        try {
            $data = $request->validated();
            $leavehistory = $this->leavehistoryService->store($data);
            return $this->success('Leave History created successfully', $leavehistory, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $leavehistory = $this->leavehistoryService->show($id);
            if (!$leavehistory) {
                return $this->error('Leave History not found', 404);
            }
            return $this->success('Leave History retrieved successfully', $leavehistory);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(LeaveHistoryRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $leavehistory = $this->leavehistoryService->update($id, $data);
            if (!$leavehistory) {
                return $this->error('Leave History not found', 404);
            }
            return $this->success('Leave History updated successfully', $leavehistory, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $leavehistory = $this->leavehistoryService->destroy($id);
            if (!$leavehistory) {
                return $this->error('Leave History not found', 404);
            }
            return $this->success('Leave History deleted successfully, id : '.$leavehistory->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
