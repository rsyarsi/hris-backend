<?php

namespace App\Repositories\Education;

use App\Models\Education;
use App\Repositories\Education\EducationRepositoryInterface;


class EducationRepository implements EducationRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(Education $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model->select($this->field);

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
        $education = $this->model->where('id', $id)->first($this->field);
        return $education ? $education : $education = null;
    }

    public function update($id, $data)
    {
        $education = $this->model->find($id);
        if ($education) {
            $education->update($data);
            return $education;
        }
        return null;
    }

    public function destroy($id)
    {
        $education = $this->model->find($id);
        if ($education) {
            $education->delete();
            return $education;
        }
        return null;
    }
}
