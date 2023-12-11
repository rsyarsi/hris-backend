<?php

namespace App\Repositories\CatatanCuti;

use App\Models\CatatanCuti;
use App\Repositories\CatatanCuti\CatatanCutiRepositoryInterface;


class CatatanCutiRepository implements CatatanCutiRepositoryInterface
{
    private $model;
    private $field =
    [
        'employee_id',
        'name',
        'relationship_id',
        'as_emergency',
        'is_dead',
        'birth_date',
        'phone',
        'phone_country',
        'address',
        'postal_code',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'job_id',
    ];

    public function __construct(CatatanCuti $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            }
                        ])->select($this->field);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->where('name', 'like', '%' . $search . '%');
                            });
            });
        }
        return $query->orderBy('id', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $catatanCuti = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'relationship' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'job' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'province' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'city' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'district' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'village' => function ($query) {
                                $query->select('id', 'name');
                            }
                        ])
                        ->where('id', $id)
                        ->first($this->field);
        return $catatanCuti ? $catatanCuti : $catatanCuti = null;
    }

    public function update($id, $data)
    {
        $catatanCuti = $this->model->find($id);
        if ($catatanCuti) {
            $catatanCuti->update($data);
            return $catatanCuti;
        }
        return null;
    }

    public function destroy($id)
    {
        $catatanCuti = $this->model->find($id);
        if ($catatanCuti) {
            $catatanCuti->delete();
            return $catatanCuti;
        }
        return null;
    }

    public function catatanCutiEmployee($perPage, $search = null, $employeeId)
    {
        $query = $this->model->select($this->field);
        if ($search !== null) {
            $query->whereRaw('year LIKE ?', "%". $search ."%");
        }
        $query->where('employee_id', $employeeId);
        return $query->orderBy('year', 'DESC')->paginate($perPage);
    }
}
