<?php

namespace App\Repositories\District;

use App\Models\District;
use App\Repositories\District\DistrictRepositoryInterface;


class DistrictRepository implements DistrictRepositoryInterface
{
    private $model;
    private $field = ['id', 'code', 'city_code', 'name', 'meta'];

    public function __construct(District $model)
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
        $district = $this->model
                    ->with(['villages' => function ($query) {
                        $query->select('district_code', 'code', 'name', 'meta');
                    }])
                    ->where('id', $id)
                    ->first($this->field);
        return $district ? $district : $district = null;
    }

    public function update($id, $data)
    {
        $district = $this->model->find($id);
        if ($district) {
            $district->update($data);
            return $district;
        }
        return null;
    }

    public function destroy($id)
    {
        $district = $this->model->find($id);
        if ($district) {
            $district->delete();
            return $district;
        }
        return null;
    }
}
