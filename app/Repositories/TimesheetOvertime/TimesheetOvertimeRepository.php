<?php

namespace App\Repositories\TimesheetOvertime;

use App\Models\Employee;
use Carbon\Carbon;
use App\Models\TimesheetOvertime;
use Illuminate\Support\Facades\DB;
use App\Services\Employee\EmployeeServiceInterface;
use App\Repositories\TimesheetOvertime\TimesheetOvertimeRepositoryInterface;

class TimesheetOvertimeRepository implements TimesheetOvertimeRepositoryInterface
{
    private $model;
    private $employeeService;
    private $field =
    [
        'id',
        'employee_id',
        'employee_name',
        'unitname',
        'positionname',
        'departmenname',
        'overtime_type',
        'realisasihours',
        'schedule_date_in_at',
        'schedule_time_in_at',
        'schedule_date_out_at',
        'schedule_time_out_at',
        'date_in_at',
        'time_in_at',
        'date_out_at',
        'time_out_at',
        'jamlemburawal',
        'jamlemburconvert',
        'jamlembur',
        'tuunjangan',
        'uanglembur',
        'period',
    ];

    public function __construct(TimesheetOvertime $model, EmployeeServiceInterface $employeeService)
    {
        $this->model = $model;
        $this->employeeService = $employeeService;
    }

    public function index($perPage, $search = null, $period)
    {
        $query = $this->model
                    ->with(['employee' => function ($query)
                        {
                            $query->select('id', 'name', 'employment_number');
                        },
                    ])
                    ->where('period', 'like', "%{$period}%")
                    ->select($this->field);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                                ->orWhere('employment_number', 'like', '%' . $search . '%');
                            });
            });
        }
        return $query->orderBy('schedule_date_in_at', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $timesheetOvertime = $this->model
                                    ->with(['employee' => function ($query)
                                        {
                                            $query->select('id', 'name', 'employment_number');
                                        },
                                    ])
                                    ->where('id', $id)
                                    ->first($this->field);
        return $timesheetOvertime ? $timesheetOvertime : $timesheetOvertime = null;
    }

    public function update($id, $data)
    {
        $timesheetOvertime = $this->model->find($id);
        if ($timesheetOvertime) {
            $timesheetOvertime->update($data);
            return $timesheetOvertime;
        }
        return null;
    }

    public function destroy($id)
    {
        $timesheetOvertime = $this->model->find($id);
        if ($timesheetOvertime) {
            $timesheetOvertime->delete();
            return $timesheetOvertime;
        }
        return null;
    }

    public function timesheetOvertimeEmployee($perPage, $search = null, $employeeId)
    {
        $query = $this->model->select($this->field);
        if ($search !== null) {
            $query->whereRaw('period LIKE ?', "%". $search ."%");
        }
        $query->where('employee_id', $employeeId);
        return $query->orderBy('schedule_date_in_at', 'DESC')->paginate($perPage);
    }

    public function executeStoredProcedure($periodeAbsenStart, $periodeAbsenEnd)
    {
        $now = Carbon::now();
        $periodGaji = Carbon::parse($periodeAbsenEnd);
        $employees = Employee::with('contract')->with([
            'contract' => function ($query) {
                $query->select('id', 'employee_id', 'transaction_number', 'start_at', 'end_at', 'hour_per_day', 'created_at')->with([
                    'employeeContractDetail:id,employee_contract_id,nominal,created_at',
                ])->orderBy('start_at', 'ASC');
            }
        ])
        ->whereNot('name', 'ADMINISTRATOR')
        ->where('resigned_at', '>=', Carbon::now()->toDateString())
        ->orderBy('name', 'ASC')
        ->get();
        $message = 'Execute Success!';
        foreach ($employees as $item) {
            // Check if the employee has a contract
            if (count($item->contract) > 0 && count($item->contract->first()->employeeContractDetail) > 0) {
                foreach ($item->contract as $contract) {
                    // echo $contract->hour_per_day.',';
                    if ($contract->employeeContractDetail !== null) {
                        foreach ($contract->employeeContractDetail as $employeeContractDetail) {
                            // echo $employeeContractDetail->id.',';
                        }
                    } else {
                        $message = 'Execute Gagal di kontrak detail';
                    }
                }
            } else {
                $message = 'Execute gagal, di kontrak';
            }
        }

        if ($message !== '') {
            return $message;
        }
        // If all employees pass the validation, proceed with stored procedures
        foreach ($employees as $item) {
            DB::select('CALL generatetempovertimes(?, ?, ?, ?, ?, ?)', [
                $now->toDateString(),
                $periodeAbsenStart,
                $periodeAbsenEnd,
                $item->id,
                $periodGaji->format('Y-m'),
                // '2024-01',
                $item->contract['0']->hour_per_day,
            ]);

            DB::select('CALL generateovertimes(?, ?, ?, ?, ?)', [
                $now->toDateString(),
                $periodeAbsenStart,
                $periodeAbsenEnd,
                $item->id,
                $periodGaji->format('Y-m')
                // '2024-01',
            ]);
        }
        return $message;
    }
}
