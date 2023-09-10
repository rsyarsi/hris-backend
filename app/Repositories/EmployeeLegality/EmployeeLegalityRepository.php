<?php

namespace App\Repositories\EmployeeLegality;

use App\Models\EmployeeLegality;
use App\Repositories\EmployeeLegality\EmployeeLegalityRepositoryInterface;


class EmployeeLegalityRepository implements EmployeeLegalityRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'employee_id',
        'legality_type_id',
        'started_at',
        'ended_at',
        'file_url',
        'file_path',
        'file_disk',
    ];

    public function __construct(EmployeeLegality $model)
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
                        'legalityType' => function ($query) {
                            $query->select('id', 'name', 'active', 'extended');
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
        $employeelegality = $this->model
                                    ->with([
                                        'employee' => function ($query) {
                                            $query->select('id', 'name');
                                        },
                                        'legalityType' => function ($query) {
                                            $query->select('id', 'name', 'active', 'extended');
                                        },
                                    ])
                                    ->where('id', $id)
                                    ->first($this->field);
        return $employeelegality ? $employeelegality : $employeelegality = null;
    }

    public function update($id, $data)
    {
        $employeelegality = $this->model->find($id);
        if ($employeelegality) {
            $employeelegality->update($data);
            return $employeelegality;
        }
        return null;
    }

    public function destroy($id)
    {
        $employeelegality = $this->model->find($id);
        if ($employeelegality) {
            $employeelegality->delete();
            return $employeelegality;
        }
        return null;
    }
}
