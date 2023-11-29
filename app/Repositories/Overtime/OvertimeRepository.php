<?php

namespace App\Repositories\Overtime;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\{Employee, Overtime};
use App\Repositories\Overtime\OvertimeRepositoryInterface;


class OvertimeRepository implements OvertimeRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'employee_id',
        'task',
        'note',
        'overtime_status_id',
        'from_date',
        'amount',
        'type',
        'to_date',
        'duration',
    ];

    public function __construct(Overtime $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'overtimeStatus' => function ($query) {
                            $query->select('id', 'name');
                        },
                    ])
                    ->select($this->field);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
                            });
            });
        }
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $overtime = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'overtimeStatus' => function ($query) {
                                $query->select('id', 'name');
                            },
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $overtime ? $overtime : $overtime = null;
    }

    public function update($id, $data)
    {
        $overtime = $this->model->find($id);
        if ($overtime) {
            $overtime->update($data);
            return $overtime;
        }
        return null;
    }

    public function destroy($id)
    {
        $overtime = $this->model->find($id);
        if ($overtime) {
            $overtime->delete();
            return $overtime;
        }
        return null;
    }

    public function overtimeEmployee($perPage, $overtimeStatus = null, $startDate = null, $endDate = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'overtimeStatus' => function ($query) {
                            $query->select('id', 'name');
                        },
                    ])
                    ->select($this->field);
        $query->where('employee_id', $user->employee->id);
        if ($overtimeStatus) {
            $query->where('overtime_status_id', $overtimeStatus);
        }
        if ($startDate) {
            $query->whereDate('from_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('from_date', '<=', $endDate);
        }
        return $query->paginate($perPage);
    }

    public function overtimeEmployeeMobile($employeeId)
    {
        $employee = Employee::where('employment_number', $employeeId)->first();
        if (!$employee) {
            return [];
        }
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $overtime = DB::table('overtimes')
                        ->select([
                            DB::raw("COALESCE(overtimes.id, '') as id"),
                            DB::raw("COALESCE(overtimes.employee_id, '') as employee_id"),
                            DB::raw("COALESCE(overtimes.task, '') as task"),
                            DB::raw("COALESCE(overtimes.note, '') as note"),
                            DB::raw("COALESCE(overtimes.overtime_status_id::text, '') as overtime_status_id"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.from_date, 'YYYY-MM-DD HH24:MI:SS'), '') as from_date"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.to_date, 'YYYY-MM-DD HH24:MI:SS'), '') as to_date"),
                            DB::raw("COALESCE(overtimes.amount::text, '') as amount"),
                            DB::raw("COALESCE(overtimes.type, '') as type"),
                            DB::raw("COALESCE(overtimes.duration::text, '') as duration"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.created_at, 'YYYY-MM-DD'), '') as tglinput"),
                            DB::raw("COALESCE(employees.name, '') as employee_name"),
                            DB::raw("COALESCE(overtime_statuses.name, '') as overtime_status_name"),
                        ])
                        ->leftJoin('employees', 'overtimes.employee_id', '=', 'employees.id')
                        ->leftJoin('overtime_statuses', 'overtimes.overtime_status_id', '=', 'overtime_statuses.id')
                        ->where('overtimes.employee_id', $employee->id)
                        ->whereBetween('overtimes.from_date', [$startOfMonth, $endOfMonth])
                        ->orderBy('overtimes.from_date', 'ASC')
                        ->get();
        return $overtime ? $overtime : $overtime = null;
    }

    public function overtimeSupervisorOrManager($perPage, $overtimeStatus = null, $startDate = null, $endDate = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }

        // Get employees supervised or managed by the logged-in user
        $subordinateIds = Employee::where('supervisor_id', $user->employee->id)
                                    ->orWhere('manager_id', $user->employee->id)
                                    ->pluck('id');
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'overtimeStatus' => function ($query) {
                            $query->select('id', 'name');
                        },
                    ])
                    ->select($this->field);

        // Filter overtime data for supervised or managed employees
        $query->whereIn('employee_id', $subordinateIds);

        if ($overtimeStatus) {
            $query->where('overtime_status_id', $overtimeStatus);
        }
        if ($startDate) {
            $query->whereDate('from_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('from_date', '<=', $endDate);
        }
        return $query->paginate($perPage);
    }

    public function overtimeStatus($perPage, $search = null, $overtimeStatus = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'overtimeStatus' => function ($query) {
                            $query->select('id', 'name');
                        },
                    ])
                    ->select($this->field);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->where('name', 'like', '%' . $search . '%');
                            });
            });
        }

        if ($overtimeStatus) {
            $query->where('overtime_status_id', $overtimeStatus);
        }
        return $query->paginate($perPage);
    }

    public function updateStatus($id, $data)
    {
        $overtime = $this->model->find($id);
        if ($overtime) {
            $overtime->update(['overtime_status_id' => $data['overtime_status_id']]);
            return $overtime;
        }
        return null;
    }

    public function overtimeEmployeeToday($employeeId)
    {
        $employee = Employee::where('employment_number', $employeeId)->first();
        if (!$employee) {
            return [];
        }
        $overtime = DB::table('overtimes')
                        ->select([
                            DB::raw("COALESCE(overtimes.id, '') as id"),
                            DB::raw("COALESCE(overtimes.employee_id, '') as employee_id"),
                            DB::raw("COALESCE(overtimes.task, '') as task"),
                            DB::raw("COALESCE(overtimes.note, '') as note"),
                            DB::raw("COALESCE(overtimes.overtime_status_id::text, '') as overtime_status_id"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.from_date, 'YYYY-MM-DD HH24:MI:SS'), '') as from_date"),
                            DB::raw("COALESCE(TO_CHAR(overtimes.to_date, 'YYYY-MM-DD HH24:MI:SS'), '') as to_date"),
                            DB::raw("COALESCE(overtimes.amount::text, '') as amount"),
                            DB::raw("COALESCE(overtimes.type, '') as type"),
                            DB::raw("COALESCE(overtimes.duration::text, '') as duration"),
                            DB::raw("COALESCE(employees.name, '') as employee_name"),
                            DB::raw("COALESCE(overtime_statuses.name, '') as overtime_status_name"),
                        ])
                        ->leftJoin('employees', 'overtimes.employee_id', '=', 'employees.id')
                        ->leftJoin('overtime_statuses', 'overtimes.overtime_status_id', '=', 'overtime_statuses.id')
                        ->where('overtimes.employee_id', $employee->id)
                        ->where('from_date', '>=', Carbon::today()->startOfDay())
                        ->where('from_date', '<', Carbon::tomorrow()->startOfDay())
                        ->first($this->field);
        return $overtime ? $overtime : $overtime = null;
    }
}
