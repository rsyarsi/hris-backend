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
