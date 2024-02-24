<?php

namespace App\Repositories\OrderOvertime;

use Carbon\Carbon;
use App\Models\GenerateAbsen;
use App\Models\ShiftSchedule;
use Illuminate\Support\Facades\DB;
use App\Services\Overtime\OvertimeServiceInterface;
use App\Services\OvertimeHistory\OvertimeHistoryServiceInterface;
use App\Models\{Employee, OrderOvertime, Overtime, OvertimeHistory};
use App\Repositories\OrderOvertime\OrderOvertimeRepositoryInterface;

class OrderOvertimeRepository implements OrderOvertimeRepositoryInterface
{
    private $model;
    private $overtimeService;
    private $overtimeHistoryService;

    public function __construct(
        OrderOvertime $model,
        OvertimeServiceInterface $overtimeService,
        OvertimeHistoryServiceInterface $overtimeHistoryService,
    )
    {
        $this->model = $model;
        $this->overtimeService = $overtimeService;
        $this->overtimeHistoryService = $overtimeHistoryService;
    }

    public function index($perPage, $search = null, $period_1 = null, $period_2 = null, $unit = null, $status = null)
    {
        $query = $this->model
                    ->with([
                        'employeeStaff' => function ($query) {
                            $query->select('id', 'name', 'employment_number', 'unit_id');
                        },
                        'employeeEntry' => function ($query) {
                            $query->select('id', 'name', 'employment_number', 'unit_id');
                        },
                        'userCreated' => function ($query) {
                            $query->select('id', 'name');
                        }
                    ]);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_staff_id', $search)
                            ->orWhereHas('employeeStaff', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                                ->orWhere('employment_number', 'like', '%' . $search . '%');
                            });
            });
        }
        if ($unit) {
            $query->whereHas('employeeStaff', function ($employeeQuery) use ($unit) {
                $employeeQuery->where('unit_id', $unit);
            });
        }
        if ($period_1) {
            $query->whereDate('from_date', '>=', $period_1);
        }
        if ($period_2) {
            $query->whereDate('to_date', '<=', $period_2);
        }
        if ($status) {
            $query->where('status', $status);
        }
        return $query->orderBy('from_date', 'DESC')->paginate($perPage);
    }

    public function indexSubOrdinate($perPage, $search = null, $period_1 = null, $period_2 = null, $unit = null, $status = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        // Get employees supervised or managed by the logged-in user
        $subordinateIds = Employee::where('supervisor_id', $user->employee->id)
                                    ->orWhere('manager_id', $user->employee->id)
                                    ->orWhere('kabag_id', $user->employee->id)
                                    ->pluck('id');
        $query = $this->model
                    ->with([
                        'employeeStaff' => function ($query) {
                            $query->select('id', 'name', 'employment_number', 'unit_id');
                        },
                        'employeeEntry' => function ($query) {
                            $query->select('id', 'name', 'employment_number', 'unit_id');
                        },
                        'userCreated' => function ($query) {
                            $query->select('id', 'name');
                        }
                    ]);

        // Filter overtime data for supervised or managed employees
        $query->whereIn('employee_staff_id', $subordinateIds);

        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_staff_id', $search)
                            ->orWhereHas('employeeStaff', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                                ->orWhere('employment_number', 'like', '%' . $search . '%');
                            });
            });
        }
        if ($unit) {
            $query->whereHas('employeeStaff', function ($employeeQuery) use ($unit) {
                $employeeQuery->where('unit_id', $unit);
            });
        }
        if ($period_1) {
            $query->whereDate('from_date', '>=', $period_1);
        }
        if ($period_2) {
            $query->whereDate('to_date', '<=', $period_2);
        }
        if ($status) {
            $query->where('status', $status);
        }
        return $query->orderBy('from_date', 'DESC')->paginate($perPage);
    }

    public function indexSubOrdinateMobile($employeeId)
    {
        $subordinateIds = Employee::where('supervisor_id', $employeeId)
                                    ->orWhere('manager_id', $employeeId)
                                    ->orWhere('kabag_id', $employeeId)
                                    ->pluck('id');
        return DB::table('order_overtimes')
                    ->leftJoin('employees AS staff', 'order_overtimes.employee_staff_id', '=', 'staff.id')
                    ->leftJoin('employees AS entry', 'order_overtimes.employee_entry_id', '=', 'entry.id')
                    ->leftJoin('users', 'order_overtimes.user_created_id', '=', 'users.id')
                    ->select([
                        'order_overtimes.id',
                        'order_overtimes.from_date',
                        'order_overtimes.from_date',
                        'order_overtimes.to_date',
                        'order_overtimes.note_order',
                        'order_overtimes.note_overtime',
                        'order_overtimes.type',
                        'order_overtimes.duration',
                        'order_overtimes.holiday',
                        'staff.name as employee_staff_name',
                        'entry.name as employee_entry_name',
                        'users.name as user_created_name',
                    ])->whereIn('order_overtimes.employee_staff_id', $subordinateIds)
                    ->orderBy('from_date', 'DESC')
                    ->get();
    }

    public function store(array $data)
    {
        $orderOvertime = $this->model->create($data);
        return [
            'message' => 'Order Overtime created successfully',
            'error' => false,
            'code' => 200,
            'data' => [$orderOvertime]
        ];
    }

    public function storeMobile(array $data)
    {
        $orderOvertime = $this->model->create($data);
        return [
            'message' => 'Overtime created successfully',
            'success' => true,
            'code' => 200,
            'data' => [$orderOvertime]
        ];
    }

    public function show($id)
    {
        $overtime = $this->model
                        ->with([
                            'employeeStaff' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                            'employeeEntry' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                            'userCreated' => function ($query) {
                                $query->select('id', 'name');
                            }
                        ])
                        ->where('id', $id)
                        ->first();
        return $overtime ? $overtime : $overtime = null;
    }

    public function update($id, $data)
    {
        $orderOvertime = $this->model->find($id);
        if (!$orderOvertime) {
            return [
                'message' => 'Order Overtime Not Found',
                'error' => true,
                'code' => 404,
                'data' => []
            ];
        }
        $orderOvertime->update($data);
        return [
            'message' => 'Order Overtime Update successfully',
            'error' => false,
            'code' => 200,
            'data' => [$orderOvertime]
        ];
    }

    public function destroy($id)
    {
        $overtime = $this->model->find($id);
        if ($overtime) {
            $this->overtimeHistoryService->deleteByOvertimeId($id);
            $overtime->delete();
            return $overtime;
        }
        return null;
    }

    public function updateStatus($id, $data)
    {
        try {
            DB::beginTransaction();

            $orderOvertime = $this->model->find($id);
            if (!$orderOvertime) {
                return [
                    'message' => 'Order Overtime Not Found',
                    'error' => true,
                    'code' => 404,
                    'data' => []
                ];
            }

            $fromDate = Carbon::parse($orderOvertime->from_date);
            $shiftSchedule = ShiftSchedule::where('employee_id', $orderOvertime->employee_staff_id)
                                            ->where('date', $fromDate->toDateString())
                                            ->first();
            if ($data['status'] == 'REJECT') {
                $orderOvertime->update($data);
            } else if ($data['status'] == 'APPROVE' && $shiftSchedule) {
                $orderOvertime->update($data);
                $overtimeData = [
                    'employee_id' => $orderOvertime->employee_staff_id,
                    'task' => $orderOvertime->note_order,
                    'note' => $orderOvertime->note_overtime . '(Data Dari Order Overtime)',
                    'overtime_status_id' => 5,
                    'from_date' => $orderOvertime->from_date,
                    'to_date' => $orderOvertime->to_date,
                    'amount' => 0,
                    'type' => $orderOvertime->type,
                    'duration' => $orderOvertime->duration,
                    'libur' => $orderOvertime->holiday,
                ];
                $overtime = Overtime::create($overtimeData);

                $historyData = [
                    'overtime_id' => $overtime->id,
                    'user_id' => auth()->id(),
                    'description' => 'OVERTIME STATUS ' . $overtime->overtimeStatus->name,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'comment' => $overtime->note,
                    'libur' => $overtime->libur,
                ];
                OvertimeHistory::create($historyData);

                $fromDateParse = Carbon::parse($overtime->from_date);
                $toDateParse = Carbon::parse($overtime->to_date);

                $data['period'] = $fromDateParse->format('Y-m');
                $data['date'] = $fromDateParse->toDateString();
                $data['day'] = $fromDateParse->format('l');
                $data['employee_id'] = $overtime->employee_id;
                $data['employment_id'] = $overtime->employee->employment_number;
                $data['shift_id'] = $shiftSchedule->shift_id;
                $data['date_in_at'] = $fromDateParse->toDateString();
                $data['time_in_at'] = $fromDateParse->toTimeString();
                $data['date_out_at'] = $toDateParse->toDateString();
                $data['time_out_at'] = $toDateParse->toTimeString();
                $data['schedule_date_in_at'] = $shiftSchedule->date;
                $data['schedule_time_in_at'] = Carbon::parse($shiftSchedule->time_in)->toTimeString();
                $data['schedule_date_out_at'] = $shiftSchedule->date;
                $data['schedule_time_out_at'] = Carbon::parse($shiftSchedule->time_out)->toTimeString();
                $data['holiday'] = $shiftSchedule->holiday;
                $data['night'] = $shiftSchedule->night;
                $data['national_holiday'] = $shiftSchedule->national_holiday;
                $data['function'] = null;
                $data['note'] = null;
                $data['type'] = 'SPL';
                $data['overtime_type'] = $overtime->type;
                $data['overtime_hours'] = $overtime->duration;
                $data['shift_schedule_id'] = $shiftSchedule->id;
                GenerateAbsen::create($data);
            } else {
                return [
                    'message' => 'Shift Schedule Not Found!',
                    'error' => true,
                    'code' => 404,
                    'data' => []
                ];
            }

            DB::commit();

            return [
                'message' => 'Order Overtime Update successfully',
                'error' => false,
                'code' => 200,
                'data' => [$orderOvertime]
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'message' => 'Error updating order overtime',
                'error' => true,
                'code' => 500,
                'data' => ['status' => [$e->getMessage()]]
            ];
        }
    }

    public function updateStatusMobile($id, $status)
    {
        try {
            DB::beginTransaction();

            $orderOvertime = $this->model->find($id);
            if (!$orderOvertime) {
                return [
                    'message' => 'Order Overtime Not Found',
                    'error' => true,
                    'code' => 200,
                    'data' => []
                ];
            }

            $fromDate = Carbon::parse($orderOvertime->from_date);
            $shiftSchedule = ShiftSchedule::where('employee_id', $orderOvertime->employee_staff_id)
                                            ->where('date', $fromDate->toDateString())
                                            ->first();
            if ($status == 'REJECT') {
                $orderOvertime->update(['status' => $status]);
            } else if ($status == 'APPROVE' && $shiftSchedule) {
                $orderOvertime->update(['status' => $status]);
                $overtimeData = [
                    'employee_id' => $orderOvertime->employee_staff_id,
                    'task' => $orderOvertime->note_order,
                    'note' => $orderOvertime->note_overtime . '(Data Dari Order Overtime)',
                    'overtime_status_id' => 5,
                    'from_date' => $orderOvertime->from_date,
                    'to_date' => $orderOvertime->to_date,
                    'amount' => 0,
                    'type' => $orderOvertime->type,
                    'duration' => $orderOvertime->duration,
                    'libur' => $orderOvertime->holiday,
                ];
                $overtime = Overtime::create($overtimeData);

                $historyData = [
                    'overtime_id' => $overtime->id,
                    'user_id' => auth()->id(),
                    'description' => 'OVERTIME STATUS ' . $overtime->overtimeStatus->name,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'comment' => $overtime->note,
                    'libur' => $overtime->libur,
                ];
                OvertimeHistory::create($historyData);

                $fromDateParse = Carbon::parse($overtime->from_date);
                $toDateParse = Carbon::parse($overtime->to_date);

                $data['period'] = $fromDateParse->format('Y-m');
                $data['date'] = $fromDateParse->toDateString();
                $data['day'] = $fromDateParse->format('l');
                $data['employee_id'] = $overtime->employee_id;
                $data['employment_id'] = $overtime->employee->employment_number;
                $data['shift_id'] = $shiftSchedule->shift_id;
                $data['date_in_at'] = $fromDateParse->toDateString();
                $data['time_in_at'] = $fromDateParse->toTimeString();
                $data['date_out_at'] = $toDateParse->toDateString();
                $data['time_out_at'] = $toDateParse->toTimeString();
                $data['schedule_date_in_at'] = $shiftSchedule->date;
                $data['schedule_time_in_at'] = Carbon::parse($shiftSchedule->time_in)->toTimeString();
                $data['schedule_date_out_at'] = $shiftSchedule->date;
                $data['schedule_time_out_at'] = Carbon::parse($shiftSchedule->time_out)->toTimeString();
                $data['holiday'] = $shiftSchedule->holiday;
                $data['night'] = $shiftSchedule->night;
                $data['national_holiday'] = $shiftSchedule->national_holiday;
                $data['function'] = null;
                $data['note'] = null;
                $data['type'] = 'SPL';
                $data['overtime_type'] = $overtime->type;
                $data['overtime_hours'] = $overtime->duration;
                $data['shift_schedule_id'] = $shiftSchedule->id;
                GenerateAbsen::create($data);
            } else {
                return [
                    'message' => 'Shift Schedule Not Found!',
                    'error' => true,
                    'code' => 200,
                    'data' => []
                ];
            }

            DB::commit();

            return [
                'message' => 'Order Overtime Update successfully',
                'error' => false,
                'code' => 200,
                'data' => [$orderOvertime]
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'message' => 'Error updating order overtime',
                'error' => true,
                'code' => 200,
                'data' => ['status' => [$e->getMessage()]]
            ];
        }
    }
}
