<?php

namespace App\Repositories\OvertimeHistory;

use App\Models\OvertimeHistory;
use App\Repositories\OvertimeHistory\OvertimeHistoryRepositoryInterface;


class OvertimeHistoryRepository implements OvertimeHistoryRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'overtime_id',
        'description',
        'ip_address',
        'user_id',
        'user_agent',
        'comment',
        'active',
    ];

    public function __construct(OvertimeHistory $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                        ->with([
                            'overtime' => function ($query) {
                                $query->select(
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
        $overtimeHistory = $this->model
                                ->with([
                                    'overtime' => function ($query) {
                                        $query->select(
                                            'id',
                                            'employee_id',
                                            'overtime_type_id',
                                            'from_date',
                                            'to_date',
                                            'duration',
                                            'note',
                                            'overtime_status_id'
                                        );
                                    },
                                    'user' => function ($query) {
                                        $query->select('id', 'name', 'email');
                                    },
                                ])
                                ->where('id', $id)
                                ->first($this->field);
        return $overtimeHistory ? $overtimeHistory : $overtimeHistory = null;
    }

    public function update($id, $data)
    {
        $overtimeHistory = $this->model->find($id);
        if ($overtimeHistory) {
            $overtimeHistory->update($data);
            return $overtimeHistory;
        }
        return null;
    }

    public function destroy($id)
    {
        $overtimeHistory = $this->model->find($id);
        if ($overtimeHistory) {
            $overtimeHistory->delete();
            return $overtimeHistory;
        }
        return null;
    }

    public function deleteByOvertimeId($overtimeId)
    {
        return $this->model->where('overtime_id', $overtimeId)->delete();
    }
}
