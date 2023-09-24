<?php

namespace App\Repositories\Province;

use App\Models\Province;
use App\Repositories\Province\ProvinceRepositoryInterface;


class ProvinceRepository implements ProvinceRepositoryInterface
{
    private $model;
    private $field = ['id', 'code', 'name', 'meta'];

    public function __construct(Province $model)
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
        $province = $this->model
                        ->with(['cities' => function ($query) {
                            $query->select('id', 'province_code', 'code', 'name', 'meta');
                        }])
                        ->where('id', $id)
                        ->first($this->field);
        return $province ? $province : $province = null;
    }

    public function update($id, $data)
    {
        $province = $this->model->find($id);
        if ($province) {
            $province->update($data);
            return $province;
        }
        return null;
    }

    public function destroy($id)
    {
        $province = $this->model->find($id);
        if ($province) {
            $province->delete();
            return $province;
        }
        return null;
    }
}
