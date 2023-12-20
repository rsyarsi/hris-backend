<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OvertimeHistoryRequest;
use App\Services\OvertimeHistory\OvertimeHistoryServiceInterface;

class OvertimeHistoryController extends Controller
{
    use ResponseAPI;

    private $overtimeHistoryService;

    public function __construct(OvertimeHistoryServiceInterface $overtimeHistoryService)
    {
        $this->middleware('auth:api');
        $this->overtimeHistoryService = $overtimeHistoryService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $overtimeHistory = $this->overtimeHistoryService->index($perPage, $search);
            return $this->success('Overtime History retrieved successfully', $overtimeHistory);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(OvertimeHistoryRequest $request)
    {
        try {
            $data = $request->validated();
            $overtimeHistory = $this->overtimeHistoryService->store($data);
            return $this->success('Overtime History created successfully', $overtimeHistory, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $overtimeHistory = $this->overtimeHistoryService->show($id);
            if (!$overtimeHistory) {
                return $this->error('Overtime History not found', 404);
            }
            return $this->success('Overtime History retrieved successfully', $overtimeHistory);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(OvertimeHistoryRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $overtimeHistory = $this->overtimeHistoryService->update($id, $data);
            if (!$overtimeHistory) {
                return $this->error('Overtime History not found', 404);
            }
            return $this->success('Overtime History updated successfully', $overtimeHistory, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $overtimeHistory = $this->overtimeHistoryService->destroy($id);
            if (!$overtimeHistory) {
                return $this->error('Overtime History not found', 404);
            }
            return $this->success('Overtime History deleted successfully, id : '.$overtimeHistory->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
