<?php

namespace App\Repositories\Job;

use App\Models\Job;
use App\Repositories\Job\JobRepositoryInterface;


class JobRepository implements JobRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'active'];

    public function __construct(Job $model)
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
        $job = $this->model->where('id', $id)->first($this->field);
        return $job ? $job : $job = null;
    }

    public function update($id, $data)
    {
        $job = $this->model->find($id);
        if ($job) {
            $job->update($data);
            return $job;
        }
        return null;
    }

    public function destroy($id)
    {
        $job = $this->model->find($id);
        if ($job) {
            $job->delete();
            return $job;
        }
        return null;
    }
}
