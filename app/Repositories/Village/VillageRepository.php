<?php

namespace App\Repositories\Village;

use App\Models\Village;
use App\Repositories\Village\VillageRepositoryInterface;


class VillageRepository implements VillageRepositoryInterface
{
    private $model;
    private $field = ['id', 'code', 'district_code', 'name', 'meta'];

    public function __construct(Village $model)
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
        $village = $this->model->where('id', $id)->first($this->field);
        return $village ? $village : $village = null;
    }

    public function update($id, $data)
    {
        $village = $this->model->find($id);
        if ($village) {
            $village->update($data);
            return $village;
        }
        return null;
    }

    public function destroy($id)
    {
        $village = $this->model->find($id);
        if ($village) {
            $village->delete();
            return $village;
        }
        return null;
    }
}
