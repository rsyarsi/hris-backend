<?php

namespace App\Repositories\CatatanCuti;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\CatatanCuti;
use Illuminate\Support\Facades\DB;
use App\Repositories\CatatanCuti\CatatanCutiRepositoryInterface;

class CatatanCutiRepository implements CatatanCutiRepositoryInterface
{
    private $model;
    private $field =
    [
        'id',
        'adjustment_cuti_id',
        'leave_id',
        'employee_id',
        'quantity_awal',
        'quantity_akhir',
        'quantity_in',
        'quantity_out',
        'type',
        'description',
        'batal',
    ];

    public function __construct(CatatanCuti $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                        ->with([
                            'adjustmentCuti' => function ($query) {
                                $query->select(
                                    'id',
                                    'employee_id',
                                    'quantity_awal',
                                    'quantity_adjustment',
                                    'quantity_akhir',
                                    'year'
                                );
                            },
                            'leave' => function ($query) {
                                $query->select(
                                    'id',
                                    'employee_id',
                                    'leave_type_id',
                                    'from_date',
                                    'to_date',
                                    'duration',
                                    'note',
                                    'leave_status_id',
                                    'quantity_cuti_awal',
                                    'sisa_cuti',
                                    'file_url',
                                )->with([
                                    'leaveType:id,name,is_salary_deduction,active,day,upload_photo',
                                    'leaveStatus:id,name',
                                ]);
                            },
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            }
                        ])->select($this->field);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                                ->orWhere('employment_number', 'like', '%' . $search . '%');
                            });
            });
        }
        return $query->orderBy('id', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $catatanCuti = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                            'relationship' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'job' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'province' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'city' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'district' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'village' => function ($query) {
                                $query->select('id', 'name');
                            }
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $catatanCuti ? $catatanCuti : $catatanCuti = null;
    }

    public function update($id, $data)
    {
        $catatanCuti = $this->model->find($id);
        if ($catatanCuti) {
            $catatanCuti->update($data);
            return $catatanCuti;
        }
        return null;
    }

    public function destroy($id)
    {
        $catatanCuti = $this->model->find($id);
        if ($catatanCuti) {
            $catatanCuti->delete();
            return $catatanCuti;
        }
        return null;
    }

    public function catatanCutiEmployee($perPage, $search = null, $employeeId)
    {
        $query = $this->model->select($this->field);
        $query->where('employee_id', $employeeId);
        return $query->orderBy('id', 'DESC')->paginate($perPage);
    }

    public function catatanCutiEmployeeLatest($employeeId)
    {
        $currentYear = now()->year;
        $catatanCuti = $this->model
                            ->select($this->field)
                            ->where('employee_id', $employeeId)
                            // ->where('batal', 0)
                            ->whereYear('created_at', $currentYear)
                            ->latest()
                            ->first(); // Retrieve the latest record
        return $catatanCuti ? $catatanCuti : $catatanCuti = null;
    }

    function updateStatus($leaveId, $data)
    {
        $catatanCuti = $this->model->where('leave_id', $leaveId)
                                    ->latest()
                                    ->first(); // Retrieve the latest record
        if ($catatanCuti) {
            $catatanCuti->update($data);
            return $catatanCuti;
        }
        return null;
    }

    public function historyPemakaianCutiAll($perPage, $search = null, $unit = null, $year = null)
    {
        $query1 = DB::table('catatan_cuti')
                    ->select([
                        'catatan_cuti.employee_id as employee_id',
                        'employees.employment_number as employment_number',
                        'employees.name as employment_name',
                        'employees.unit_id',
                        DB::raw('DATE(adjustment_cuti.created_at) as date_awal'),
                        DB::raw('DATE(adjustment_cuti.created_at) as date_akhir'),
                        'catatan_cuti.quantity_awal',
                        'catatan_cuti.quantity_in',
                        'catatan_cuti.quantity_out',
                        'catatan_cuti.quantity_akhir',
                        'catatan_cuti.type',
                        'catatan_cuti.description',
                        'adjustment_cuti.year',
                        'catatan_cuti.created_at',
                    ])
                    ->join('adjustment_cuti', 'catatan_cuti.adjustment_cuti_id', '=', 'adjustment_cuti.id')
                    ->join('employees', 'catatan_cuti.employee_id', '=', 'employees.id');

        $query2 = DB::table('catatan_cuti')
                    ->select([
                        'catatan_cuti.employee_id as employee_id',
                        'employees.employment_number as employment_number',
                        'employees.name as employment_name',
                        'employees.unit_id',
                        DB::raw('DATE(leaves.from_date) as date_awal'),
                        DB::raw('DATE(leaves.to_date) as date_akhir'),
                        'catatan_cuti.quantity_awal',
                        'catatan_cuti.quantity_in',
                        'catatan_cuti.quantity_out',
                        'catatan_cuti.quantity_akhir',
                        'catatan_cuti.type',
                        'catatan_cuti.description',
                        'leaves.year',
                        'catatan_cuti.created_at',
                    ])
                    ->join('leaves', 'catatan_cuti.leave_id', '=', 'leaves.id')
                    ->join('employees', 'catatan_cuti.employee_id', '=', 'employees.id');
        if ($unit) {
            $query1->where('employees.unit_id', $unit);
            $query2->where('employees.unit_id', $unit);
        }
        if ($year) {
            $query1->where('catatan_cuti.year', $year);
            $query2->where('catatan_cuti.year', $year);
        }
        if ($search) {
            $query1->where(function ($query) use ($search) {
                $query->where('employees.id', 'like', '%' . $search . '%')
                    ->orWhere('employees.name', 'like', '%' . $search . '%')
                    ->orWhere('employees.employment_number', 'like', '%' . $search . '%');
            });

            $query2->where(function ($query) use ($search) {
                $query->where('employees.id', 'like', '%' . $search . '%')
                    ->orWhere('employees.name', 'like', '%' . $search . '%')
                    ->orWhere('employees.employment_number', 'like', '%' . $search . '%');
            });
        }
        $query = $query1->unionAll($query2);
        return $query->orderBy('created_at', 'ASC')->paginate($perPage);
    }

    public function historyPemakaianCutiSubordinate($perPage, $search = null, $unit = null, $year = null)
    {
        $user = auth()->user();
        if (!$user->employee) {
            return [];
        }
        $queryEmployee = Employee::where(function ($q) use ($user) {
                            $q->where('supervisor_id', $user->employee->id)
                                ->orWhere('manager_id', $user->employee->id)
                                ->orWhere('kabag_id', $user->employee->id);
                        })
                        ->get();
        $employeeIds = []; // Collect employee IDs in an array
        foreach ($queryEmployee as $item) {
            $employeeIds[] = $item->id;
        }
        $query1 = DB::table('catatan_cuti')
                    ->select([
                        'catatan_cuti.employee_id as employee_id',
                        'employees.employment_number as employment_number',
                        'employees.name as employment_name',
                        'employees.unit_id',
                        DB::raw('DATE(adjustment_cuti.created_at) as date_awal'),
                        DB::raw('DATE(adjustment_cuti.created_at) as date_akhir'),
                        'catatan_cuti.quantity_awal',
                        'catatan_cuti.quantity_in',
                        'catatan_cuti.quantity_out',
                        'catatan_cuti.quantity_akhir',
                        'catatan_cuti.type',
                        'catatan_cuti.description',
                        'adjustment_cuti.year',
                        'catatan_cuti.created_at',
                    ])
                    ->join('adjustment_cuti', 'catatan_cuti.adjustment_cuti_id', '=', 'adjustment_cuti.id')
                    ->join('employees', 'catatan_cuti.employee_id', '=', 'employees.id')
                    ->whereIn('employees.id', $employeeIds);

        $query2 = DB::table('catatan_cuti')
                    ->select([
                        'catatan_cuti.employee_id as employee_id',
                        'employees.employment_number as employment_number',
                        'employees.name as employment_name',
                        'employees.unit_id',
                        DB::raw('DATE(leaves.from_date) as date_awal'),
                        DB::raw('DATE(leaves.to_date) as date_akhir'),
                        'catatan_cuti.quantity_awal',
                        'catatan_cuti.quantity_in',
                        'catatan_cuti.quantity_out',
                        'catatan_cuti.quantity_akhir',
                        'catatan_cuti.type',
                        'catatan_cuti.description',
                        'leaves.year',
                        'catatan_cuti.created_at',
                    ])
                    ->join('leaves', 'catatan_cuti.leave_id', '=', 'leaves.id')
                    ->join('employees', 'catatan_cuti.employee_id', '=', 'employees.id')
                    ->whereIn('employees.id', $employeeIds);
        if ($unit) {
            $query1->where('employees.unit_id', $unit);
            $query2->where('employees.unit_id', $unit);
        }
        if ($year) {
            $query1->where('catatan_cuti.year', $year);
            $query2->where('catatan_cuti.year', $year);
        }
        if ($search) {
            $query1->where(function ($query) use ($search) {
                $query->where('employees.id', 'like', '%' . $search . '%')
                    ->orWhere('employees.name', 'like', '%' . $search . '%')
                    ->orWhere('employees.employment_number', 'like', '%' . $search . '%');
            });

            $query2->where(function ($query) use ($search) {
                $query->where('employees.id', 'like', '%' . $search . '%')
                    ->orWhere('employees.name', 'like', '%' . $search . '%')
                    ->orWhere('employees.employment_number', 'like', '%' . $search . '%');
            });
        }
        $query = $query1->unionAll($query2);
        return $query->orderBy('created_at', 'ASC')->paginate($perPage);
    }
}
