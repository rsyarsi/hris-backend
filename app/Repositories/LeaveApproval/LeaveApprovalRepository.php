<?php

namespace App\Repositories\LeaveApproval;

use App\Models\LeaveApproval;
use App\Repositories\LeaveApproval\LeaveApprovalRepositoryInterface;


class LeaveApprovalRepository implements LeaveApprovalRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'leave_id',
        'manager_id',
        'action',
        'action_at',
    ];

    public function __construct(LeaveApproval $model)
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
                        'manager' => function ($query) {
                            $query->select('id', 'name', 'email');
                        },
                    ])
                    ->select($this->field);
        // if ($search !== null) {
        //     $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
        // }
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $leaveapproval = $this->model
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
                                    'manager' => function ($query) {
                                        $query->select(
                                            'id',
                                            'name',
                                        );
                                    },
                                ])
                                ->where('id', $id)
                                ->first($this->field);
        return $leaveapproval ? $leaveapproval : $leaveapproval = null;
    }

    public function update($id, $data)
    {
        $leaveapproval = $this->model->find($id);
        if ($leaveapproval) {
            $leaveapproval->update($data);
            return $leaveapproval;
        }
        return null;
    }

    public function destroy($id)
    {
        $leaveapproval = $this->model->find($id);
        if ($leaveapproval) {
            $leaveapproval->delete();
            return $leaveapproval;
        }
        return null;
    }
}
