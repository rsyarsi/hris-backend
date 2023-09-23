<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OvertimeStatusRequest;
use App\Services\OvertimeStatus\OvertimeStatusServiceInterface;

class OvertimeStatusController extends Controller
{
    use ResponseAPI;

    private $overtimestatusService;

    public function __construct(OvertimeStatusServiceInterface $overtimestatusService)
    {
        $this->middleware('auth:api');
        $this->overtimestatusService = $overtimestatusService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $overtimestatuss = $this->overtimestatusService->index($perPage, $search);
            return $this->success('Overtime Statuses retrieved successfully', $overtimestatuss);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(OvertimeStatusRequest $request)
    {
        try {
            $data = $request->validated();
            $overtimestatus = $this->overtimestatusService->store($data);
            return $this->success('Overtime Status created successfully', $overtimestatus, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $overtimestatus = $this->overtimestatusService->show($id);
            if (!$overtimestatus) {
                return $this->error('Overtime Status not found', 404);
            }
            return $this->success('Overtime Status retrieved successfully', $overtimestatus);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(OvertimeStatusRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $overtimestatus = $this->overtimestatusService->update($id, $data);
            if (!$overtimestatus) {
                return $this->error('Overtime Status not found', 404);
            }
            return $this->success('Overtime Status updated successfully', $overtimestatus, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $overtimestatus = $this->overtimestatusService->destroy($id);
            if (!$overtimestatus) {
                return $this->error('Overtime Status not found', 404);
            }
            return $this->success('Overtime Status deleted successfully, id : '.$overtimestatus->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
