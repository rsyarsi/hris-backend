<?php

namespace App\Repositories\CatatanCuti;

use App\Models\CatatanCuti;
use App\Repositories\CatatanCuti\CatatanCutiRepositoryInterface;
use Carbon\Carbon;

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
                                $employeeQuery->where('name', 'like', '%' . $search . '%');
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
                                $query->select('id', 'name');
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
                            ->where('batal', 0)
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
}
