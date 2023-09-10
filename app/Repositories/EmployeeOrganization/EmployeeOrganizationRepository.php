<?php

namespace App\Repositories\EmployeeOrganization;

use App\Models\EmployeeOrganization;
use App\Repositories\EmployeeOrganization\EmployeeOrganizationRepositoryInterface;


class EmployeeOrganizationRepository implements EmployeeOrganizationRepositoryInterface
{
    private $model;
    private $field = ['id', 'employee_id', 'institution_name', 'position', 'started_year', 'ended_year'];

    public function __construct(EmployeeOrganization $model)
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
        $employeeorganization = $this->model
                                    ->with([
                                        'employee' => function ($query) {
                                            $query->select('id', 'name');
                                        },
                                    ])
                                    ->where('id', $id)
                                    ->first($this->field);
        return $employeeorganization ? $employeeorganization : $employeeorganization = null;
    }

    public function update($id, $data)
    {
        $employeeorganization = $this->model->find($id);
        if ($employeeorganization) {
            $employeeorganization->update($data);
            return $employeeorganization;
        }
        return null;
    }

    public function destroy($id)
    {
        $employeeorganization = $this->model->find($id);
        if ($employeeorganization) {
            $employeeorganization->delete();
            return $employeeorganization;
        }
        return null;
    }
}
