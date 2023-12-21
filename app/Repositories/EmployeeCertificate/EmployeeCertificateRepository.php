<?php

namespace App\Repositories\EmployeeCertificate;

use App\Models\EmployeeCertificate;
use App\Repositories\EmployeeCertificate\EmployeeCertificateRepositoryInterface;


class EmployeeCertificateRepository implements EmployeeCertificateRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'employee_id',
        'name',
        'institution_name',
        'started_at',
        'ended_at',
        'file_url',
        'file_path',
        'file_disk',
        'verified_at',
        'verified_user_Id',
        'is_extended',
    ];

    public function __construct(EmployeeCertificate $model)
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
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
                            });
            });
        }
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $employeecertificate = $this->model
                                    ->with([
                                        'employee' => function ($query) {
                                            $query->select('id', 'name');
                                        },
                                    ])
                                    ->where('id', $id)
                                    ->first($this->field);
        return $employeecertificate ? $employeecertificate : $employeecertificate = null;
    }

    public function update($id, $data)
    {
        $employeecertificate = $this->model->find($id);
        if ($employeecertificate) {
            $employeecertificate->update($data);
            return $employeecertificate;
        }
        return null;
    }

    public function destroy($id)
    {
        $employeecertificate = $this->model->find($id);
        if ($employeecertificate) {
            $employeecertificate->delete();
            return $employeecertificate;
        }
        return null;
    }
}
