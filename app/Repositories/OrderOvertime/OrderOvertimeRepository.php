<?php

namespace App\Repositories\OrderOvertime;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\Firebase\FirebaseServiceInterface;
use App\Services\Overtime\OvertimeServiceInterface;
use App\Services\OvertimeHistory\OvertimeHistoryServiceInterface;
use App\Repositories\OrderOvertime\OrderOvertimeRepositoryInterface;
use App\Models\{Employee, OrderOvertime, User, ShiftSchedule, GenerateAbsen, OvertimeHistory};

class OrderOvertimeRepository implements OrderOvertimeRepositoryInterface
{
    private $model;
    private $overtimeService;
    private $overtimeHistoryService;
    private $firebaseService;
    private $employeeService;
    public function __construct(
        OrderOvertime $model,
        OvertimeServiceInterface $overtimeService,
        FirebaseServiceInterface $firebaseService,
        EmployeeServiceInterface $employeeService,
        OvertimeHistoryServiceInterface $overtimeHistoryService,
    )
    {
        $this->model = $model;
        $this->overtimeService = $overtimeService;
        $this->firebaseService = $firebaseService;
        $this->employeeService = $employeeService;
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
}
