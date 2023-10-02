<?php

namespace App\Repositories\Leave;

use App\Models\Leave;
use App\Services\LeaveHistory\LeaveHistoryService;
use App\Repositories\Leave\LeaveRepositoryInterface;


class LeaveRepository implements LeaveRepositoryInterface
{
    private $model;
    private $leaveHistory;

    private $field = [
        'id',
        'employee_id',
        'leave_type_id',
        'from_date',
        'to_date',
        'duration',
        'note',
        'leave_status_id',
    ];

    public function __construct(Leave $model, LeaveHistoryService $leaveHistory)
    {
        $this->model = $model;
        $this->leaveHistory = $leaveHistory;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'leaveType' => function ($query) {
                            $query->select('id', 'name', 'is_salary_deduction', 'active');
                        },
                        'leaveStatus' => function ($query) {
                            $query->select('id', 'name');
                        },
                        'leaveHistory' => function ($query) {
                            $query->select(
                                'id',
                                'leave_id',
                                'description',
                                'ip_address',
                                'user_id',
                                'user_agent',
                                'comment',
                            );
                        }
                    ])
                    ->select($this->field);
        return $query->orderBy('from_date', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        $leave = $this->model->create($data);
        $historyData = [
            'leave_id' => $leave->id,
            'user_id' => auth()->id(),
            'description' => 'Leave Created',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'comment' => $data['note'],
        ];
        $this->leaveHistory->store($historyData);
        return $leave;
    }

    public function show($id)
    {
        $leave = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'leaveType' => function ($query) {
                                $query->select('id', 'name', 'is_salary_deduction', 'active');
                            },
                            'leaveStatus' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'leaveHistory' => function ($query) {
                                $query->select(
                                    'id',
                                    'leave_id',
                                    'description',
                                    'ip_address',
                                    'user_id',
                                    'user_agent',
                                    'comment',
                                );
                            },
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $leave ? $leave : $leave = null;
    }

    public function update($id, $data)
    {
        $leave = $this->model->with('leaveStatus')->find($id);
        $historyData = [
            'leave_id' => $leave->id,
            'user_id' => auth()->id(),
            'description' => $leave->leaveStatus->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'comment' => $data['note'],
        ];
        $this->leaveHistory->store($historyData);
        if ($leave) {
            $leave->update($data);
            return $leave;
        }
        return null;
    }

    public function destroy($id)
    {
        $leave = $this->model->find($id);
        if ($leave) {
            $leave->delete();
            return $leave;
        }
        return null;
    }
}
