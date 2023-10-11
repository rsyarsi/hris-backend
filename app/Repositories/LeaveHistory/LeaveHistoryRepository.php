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
        $leavehistory = $this->model
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
        return $leavehistory ? $leavehistory : $leavehistory = null;
    }

    public function update($id, $data)
    {
        $leavehistory = $this->model->find($id);
        if ($leavehistory) {
            $leavehistory->update($data);
            return $leavehistory;
        }
        return null;
    }

    public function destroy($id)
    {
        $leavehistory = $this->model->find($id);
        if ($leavehistory) {
            $leavehistory->delete();
            return $leavehistory;
        }
        return null;
    }

    public function deleteByLeaveId($leaveId)
    {
        return $this->model->where('leave_id', $leaveId)->delete();
    }
}
