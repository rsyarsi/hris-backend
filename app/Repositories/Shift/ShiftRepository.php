<?php

namespace App\Repositories\Shift;

use App\Models\Shift;
use App\Repositories\Shift\ShiftRepositoryInterface;


class ShiftRepository implements ShiftRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'shift_group_id',
        'code',
        'name',
        'in_time',
        'out_time',
        'finger_in_less',
        'finger_in_more',
        'finger_out_less',
        'finger_out_more',
        'finger_out_less',
        'night_shift',
        'active',
        'user_created_id',
        'user_updated_id',
        'libur',
    ];

    public function __construct(Shift $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'shiftGroup' => function ($query) {
                            $query->select('id', 'name', 'hour', 'day', 'type');
                        },
                        'userCreated' => function ($query) {
                            $query->select('id', 'name', 'email');
                        },
                        'userUpdated' => function ($query) {
                            $query->select('id', 'name', 'email');
                        },
                    ])
                    ->select($this->field);
        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
        }
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $shift = $this->model
                        ->with([
                            'shiftGroup' => function ($query) {
                                $query->select('id', 'name', 'hour', 'day', 'type');
                            },
                            'userCreated' => function ($query) {
                                $query->select('id', 'name', 'email');
                            },
                            'userUpdated' => function ($query) {
                                $query->select('id', 'name', 'email');
                            },
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $shift ? $shift : $shift = null;
    }

    public function update($id, $data)
    {
        $shift = $this->model->find($id);
        if ($shift) {
            $shift->update($data);
            return $shift;
        }
        return null;
    }

    public function destroy($id)
    {
        $shift = $this->model->find($id);
        if ($shift) {
            $shift->delete();
            return $shift;
        }
        return null;
    }
}
