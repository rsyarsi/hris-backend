<?php

namespace App\Repositories\LogFinger;

use App\Models\LogFinger;
use App\Repositories\LogFinger\LogFingerRepositoryInterface;


class LogFingerRepository implements LogFingerRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'log_at',
        'employee_id',
        'in_out',
        'code_sn_finger',
        'datetime',
        'manual',
        'user_manual_id',
        'input_manual_at',
        'code_pin'
    ];

    public function __construct(LogFinger $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model->select($this->field);
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
}
