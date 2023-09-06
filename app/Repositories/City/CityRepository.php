<?php

namespace App\Repositories\City;

use App\Models\City;
use App\Repositories\City\CityRepositoryInterface;


class CityRepository implements CityRepositoryInterface
{
    private $model;
    private $field = ['id', 'code', 'province_code', 'name', 'meta'];

    public function __construct(City $model)
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
        $city = $this->model
                    ->with(['districs' => function ($query) {
                        $query->select('city_code', 'code', 'name', 'meta');
                    }])
                    ->where('id', $id)
                    ->first($this->field);
        return $city ? $city : $city = null;
    }

    public function update($id, $data)
    {
        $city = $this->model->find($id);
        if ($city) {
            $city->update($data);
            return $city;
        }
        return null;
    }

    public function destroy($id)
    {
        $city = $this->model->find($id);
        if ($city) {
            $city->delete();
            return $city;
        }
        return null;
    }
}
