<?php

namespace App\Repositories\LogFinger;

use App\Models\LogFinger;
use App\Repositories\LogFinger\LogFingerTempRepositoryInterface;


class LogFingerTempRepository implements LogFingerRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'date_log',
        'employee_id',
        'function',
        'snfinger',
        'manual',
        'user_manual',
        'manual_date',
        'pin',
    ];

    public function __construct(LogFinger $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name');
                            },
                        ])
                        ->select($this->field);
        return $query->orderBy('datetime', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $logfinger = $this->model->where('id', $id)->first($this->field);
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

    public function logFingerUser($perPage, $search = null)
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
        $query->where('employee_id', $user->employee->id);
        return $query->orderBy('datetime', 'ASC')->paginate($perPage);
    }
}
