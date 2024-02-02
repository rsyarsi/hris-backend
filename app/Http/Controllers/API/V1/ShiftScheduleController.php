<?php

namespace App\Http\Controllers\API\V1;

use App\Traits\ResponseAPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\ShiftScheduleImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\ShiftSchedule\ShiftScheduleServiceInterface;
use App\Http\Requests\{ShiftScheduleRequest, ImportShiftScheduleRequest};
use Illuminate\Support\Facades\Log;

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

    public function shiftScheduleKehadiranEmployee(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $employeeId = $request->input('employee_id');
        $shiftSchedules = $this->shiftScheduleService->shiftScheduleKehadiranEmployee($employeeId, $perPage, $startDate, $endDate);
        return $this->success('Shift Schedule & kehadiran retrieved successfully', $shiftSchedules);
        try {
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function shiftScheduleSubordinate(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $search = $request->input('search');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $shiftSchedules = $this->shiftScheduleService->shiftScheduleSubordinate($perPage, $search, $startDate, $endDate);
            return $this->success('Shift Schedule Employee Subordinate retrieved successfully', $shiftSchedules);
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

    public function shiftScheduleEmployeeDate(Request $request)
    {
        try {
            $employeeId = $request->input('employee_id');
            $date = $request->input('date');
            $shiftSchedules = $this->shiftScheduleService->shiftScheduleEmployeeDate($employeeId, $date);
            if ($shiftSchedules) {
                return $this->success('Shift schedule berhasil diambil!', $shiftSchedules);
            } else {
                return $this->error('Karyawan & tanggal tidak ditemukan!', 404);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function shiftScheduleEmployeeMobile(Request $request)
    {
        try {
            $employeeId = $request->input('employment_id');
            $shiftSchedules = $this->shiftScheduleService->shiftScheduleEmployeeMobile($employeeId);
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
                return response()->json([
                    'message' => 'Jadwal Karyawan hari ini berhasil diambil!',
                    'success' => 'true',
                    'code' => 200,
                    'data' => $shiftSchedules,
                ]);
            } else {
                return response()->json([
                    'message' => 'Karyawan tidak ditemukan atau tidak memiliki jadwal hari ini.',
                    'success' => 'false',
                    'code' => 404,
                    'data' => [],
                ]);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function storeMultiple(ShiftScheduleRequest $request)
    {
        $data = $request->validated();
        $shiftSchedule = $this->shiftScheduleService->storeMultiple($data);
        return $this->success('Shift schedule multiple created successfully', $shiftSchedule, 201);
        try {
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function importShiftSchedule(ImportShiftScheduleRequest $request)
    {
        try {
            $importShiftSchedule = Excel::import(new ShiftScheduleImport, request()->file('file'));
            // return response()->json([
            //     'message' => $importShiftSchedule['message'],
            //     'success' => $importShiftSchedule['success'],
            //     'code' => $importShiftSchedule['code'],
            //     'data' => $importShiftSchedule['data']
            // ], $importShiftSchedule['code']);
            return $this->success('Shift schedule imported successfully!', $importShiftSchedule, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function generateShiftScheduleNonShift()
    {
        $shiftSchedule = $this->shiftScheduleService->generateShiftScheduleNonShift();
        return response()->json([
            'message' => $shiftSchedule['message'],
            'success' => $shiftSchedule['success'],
            'code' => $shiftSchedule['code'],
            'data' => $shiftSchedule['data'],
        ]);
        Log::log('Generate Abesen Non Shift successfully!');
    }
}
