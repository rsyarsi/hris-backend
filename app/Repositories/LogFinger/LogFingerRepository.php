<?php

namespace App\Repositories\LogFinger;

use App\Models\LogFinger;
use App\Repositories\LogFinger\LogFingerRepositoryInterface;


class LogFingerRepository implements LogFingerRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'employee_id',
        'code_sn_finger',
        'datetime',
        'manual',
        'user_manual_id',
        'input_manual_at',
        'code_pin',
        'time_in',
        'time_out',
        'tgl_log',
        'absen_type',
        'function'
    ];

    public function __construct(LogFinger $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null, $startDate = null, $endDate = null)
    {
        $query = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                        ])
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
        if ($startDate) {
            $query->whereDate('datetime', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('datetime', '<=', $endDate);
        }
        return $query->orderBy('datetime', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $logfinger = $this->model
                            ->with([
                                'employee' => function ($query) {
                                    $query->select('id', 'name', 'employment_number');
                                },
                            ])
                            ->where('id', $id)
                            ->first($this->field);
        return $logfinger ? $logfinger : $logfinger = null;
    }

    public function update($id, $data)
    {
        $logfinger = $this->model->find($id);
        if ($logfinger) {
            $logfinger->update($data);
            return $logfinger;
        }
        return null;
    }

    public function destroy($id)
    {
        $logfinger = $this->model->find($id);
        if ($logfinger) {
            $logfinger->delete();
            return $logfinger;
        }
        return null;
    }

    public function logFingerUser($perPage, $startDate = null, $endDate = null)
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
                        ])
                        ->select($this->field);
        if ($startDate) {
            $query->whereDate('datetime', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('datetime', '<=', $endDate);
        }
        $query->where('employee_id', $user->employee->id);
        return $query->orderBy('datetime', 'ASC')->paginate($perPage);
    }
}
