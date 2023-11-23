<?php

namespace App\Repositories\LogFingerTemp;

use App\Models\LogFingerTemp;
use App\Repositories\LogFingerTemp\LogFingerTempRepositoryInterface;


class LogFingerTempRepository implements LogFingerTempRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'date_log',
        'employee_id',
        'function',
        'snfinger',
        'absen',
        'manual',
        'user_manual',
        'manual_date',
        'pin',
    ];

    public function __construct(LogFingerTemp $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null, $startDate = null, $endDate = null)
    {
        $query = $this->model
                        ->with([
                            'employee' => function ($query) {
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
        if ($startDate) {
            $query->whereDate('date_log', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('date_log', '<=', $endDate);
        }
        return $query->orderBy('date_log', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $logfingerTemp = $this->model
                            ->with([
                                'employee' => function ($query) {
                                    $query->select('id', 'name');
                                },
                            ])
                            ->where('id', $id)
                            ->first($this->field);
        return $logfingerTemp ? $logfingerTemp : $logfingerTemp = null;
    }

    public function update($id, $data)
    {
        $logfingerTemp = $this->model->find($id);
        if ($logfingerTemp) {
            $logfingerTemp->update($data);
            return $logfingerTemp;
        }
        return null;
    }

    public function destroy($id)
    {
        $logfingerTemp = $this->model->find($id);
        if ($logfingerTemp) {
            $logfingerTemp->delete();
            return $logfingerTemp;
        }
        return null;
    }

    public function logFingerTempUser($perPage, $startDate = null, $endDate = null)
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
