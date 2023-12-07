<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TimesheetOvertimeRequest;
use App\Services\TimesheetOvertime\TimesheetOvertimeServiceInterface;

class TimesheetOvertimeController extends Controller
{
    use ResponseAPI;

    private $timesheetOvertimeService;

    public function __construct(TimesheetOvertimeServiceInterface $timesheetOvertimeService)
    {
        $this->middleware('auth:api');
        $this->timesheetOvertimeService = $timesheetOvertimeService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $timesheetOvertime = $this->timesheetOvertimeService->index($perPage, $search);
            return $this->success('TimesheetOvertime retrieved successfully', $timesheetOvertime);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(TimesheetOvertimeRequest $request)
    {
        try {
            $data = $request->validated();
            $timesheetOvertime = $this->timesheetOvertimeService->store($data);
            return $this->success('TimesheetOvertime created successfully', $timesheetOvertime, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $timesheetOvertime = $this->timesheetOvertimeService->show($id);
            if (!$timesheetOvertime) {
                return $this->error('TimesheetOvertime not found', 404);
            }
            return $this->success('TimesheetOvertime retrieved successfully', $timesheetOvertime);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(TimesheetOvertimeRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $timesheetOvertime = $this->timesheetOvertimeService->update($id, $data);
            if (!$timesheetOvertime) {
                return $this->error('TimesheetOvertime not found', 404);
            }
            return $this->success('TimesheetOvertime updated successfully', $timesheetOvertime, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $timesheetOvertime = $this->timesheetOvertimeService->destroy($id);
            if (!$timesheetOvertime) {
                return $this->error('TimesheetOvertime not found', 404);
            }
            return $this->success('TimesheetOvertime deleted successfully, id : '.$timesheetOvertime->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function timesheetovertimeEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $timesheetOvertime = $this->timesheetOvertimeService->timesheetovertimeEmployee($perPage, $search);
            return $this->success('TimesheetOvertime employee retrieved successfully', $timesheetOvertime);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
