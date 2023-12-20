<?php

namespace App\Repositories\LeaveHistory;

use App\Models\LeaveHistory;
use App\Repositories\LeaveHistory\LeaveHistoryRepositoryInterface;


class LeaveHistoryRepository implements LeaveHistoryRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'leave_id',
        'description',
        'ip_address',
        'user_id',
        'user_agent',
        'comment',
    ];

    public function __construct(LeaveHistory $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                        ->with([
                            'leave' => function ($query) {
                                $query->select(
                                    'id',
                                    'employee_id',
                                    'leave_type_id',
                                    'from_date',
                                    'to_date',
                                    'duration',
                                    'note',
                                    'leave_status_id'
                                );
                            },
                            'user' => function ($query) {
                                $query->select('id', 'name', 'email');
                            },
                        ])
                        ->select($this->field);
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $leaveHistory = $this->model
                            ->with([
                                'leave' => function ($query) {
                                    $query->select(
                                        'id',
                                        'employee_id',
                                        'leave_type_id',
                                        'from_date',
                                        'to_date',
                                        'duration',
                                        'note',
                                        'leave_status_id'
                                    );
                                },
                                'user' => function ($query) {
                                    $query->select('id', 'name', 'email');
                                },
                            ])
                            ->where('id', $id)
                            ->first($this->field);
        return $leaveHistory ? $leaveHistory : $leaveHistory = null;
    }

    public function update($id, $data)
    {
        $leaveHistory = $this->model->find($id);
        if ($leaveHistory) {
            $leaveHistory->update($data);
            return $leaveHistory;
        }
        return null;
    }

    public function destroy($id)
    {
        $leaveHistory = $this->model->find($id);
        if ($leaveHistory) {
            $leaveHistory->delete();
            return $leaveHistory;
        }
        return null;
    }

    public function deleteByLeaveId($leaveId)
    {
        return $this->model->where('leave_id', $leaveId)->delete();
    }
}
