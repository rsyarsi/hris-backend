<?php

namespace App\Repositories\Role;

use Spatie\Permission\Models\Role;
use App\Repositories\Role\RoleRepositoryInterface;


class RoleRepository implements RoleRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'guard_name'];

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model->select($this->field);
        if ($search !== null) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
        }
        return $query->orderBy('id', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $role = $this->model->where('id', $id)->first($this->field);
        return $role ? $role : $role = null;
    }

    public function update($id, $data)
    {
        $role = $this->model->find($id);
        if ($role) {
            $role->update($data);
            return $role;
        }
        return null;
    }

    public function destroy($id)
    {
        $role = $this->model->find($id);
        if ($role) {
            $role->delete();
            return $role;
        }
        return null;
    }
}
