<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\ShiftScheduleImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use App\Http\Requests\{ShiftScheduleRequest, ImportShiftScheduleRequest};

class ShiftScheduleController extends Controller
{
    use ResponseAPI;

    private $shiftScheduleService;

    public function __construct(ShiftScheduleServiceInterface $shiftScheduleService)
    {
        $this->middleware('auth:api');
        $this->shiftScheduleService = $shiftScheduleService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $shiftSchedules = $this->shiftScheduleService->index($perPage, $search, $startDate, $endDate);
            return $this->success('Shift Schedule retrieved successfully', $shiftSchedules);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function store(ShiftScheduleRequest $request)
    {
        try {
            $data = $request->validated();
            $shiftSchedule = $this->shiftScheduleService->store($data);
            return $this->success('Shift Schedule created successfully', $shiftSchedule, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $shiftSchedule = $this->shiftScheduleService->show($id);
            if (!$shiftSchedule) {
                return $this->error('Shift Schedule not found', 404);
            }
            return $this->success('Shift Schedule retrieved successfully', $shiftSchedule);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function update(ShiftScheduleRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $shiftSchedule = $this->shiftScheduleService->update($id, $data);
            if (!$shiftSchedule) {
                return $this->error('Shift Schedule not found', 404);
            }
            return $this->success('Shift Schedule updated successfully', $shiftSchedule, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $shiftSchedule = $this->shiftScheduleService->destroy($id);
            if (!$shiftSchedule) {
                return $this->error('Shift Schedule not found', 404);
            }
            return $this->success('Shift Schedule deleted successfully, id : '.$shiftSchedule->id, []);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function shiftScheduleEmployee(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $shiftSchedules = $this->shiftScheduleService->shiftScheduleEmployee($perPage, $startDate, $endDate);
            return $this->success('Shift schedule employee retrieved successfully', $shiftSchedules);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function shiftScheduleEmployeeToday(Request $request)
    {
        try {
            $employeeId = $request->input('employment_id');
            $shiftSchedules = $this->shiftScheduleService->shiftScheduleEmployeeToday($employeeId);
            if ($shiftSchedules) {
                $shiftSchedulesArray = $shiftSchedules->toArray();
                return response()->json([
                    'message' => 'Jadwal Karyawan hari ini berhasil diambil!',
                    'success' => 'true',
                    'code' => 200,
                    'data' => [$shiftSchedulesArray],
                ]);
            } else {
                return response()->json([
                    'message' => 'Karyawan tidak ditemukan atau tidak memiliki jadwal hari ini.',
                    'success' => 'false',
                    'code' => 404,
                    'data' => null,
                ]);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function storeMultiple(ShiftScheduleRequest $request)
    {
        try {
            $data = $request->validated();
            $shiftSchedule = $this->shiftScheduleService->storeMultiple($data);
            return $this->success('Shift schedule created successfully', $shiftSchedule, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function importShiftSchedule(ImportShiftScheduleRequest $request)
    {
        try {
            Excel::import(new ShiftScheduleImport, request()->file('file'));
            return $this->success('Shift schedule imported successfully', [], 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
