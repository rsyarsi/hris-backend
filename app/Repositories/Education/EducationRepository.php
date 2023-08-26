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

    public function index()
    {
        return $this->model->orderBy('id', 'ASC')->get($this->field);
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
