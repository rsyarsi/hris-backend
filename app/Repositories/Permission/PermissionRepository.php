<?php

namespace App\Repositories\Permission;

use Spatie\Permission\Models\Permission;
use App\Repositories\Permission\PermissionRepositoryInterface;


class PermissionRepository implements PermissionRepositoryInterface
{
    private $model;
    private $field = ['id', 'name', 'guard_name'];

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                        ->with([
                            'roles' => function ($query) {
                                $query->select('id', 'name', 'guard_name');
                            },
                        ])
                        ->select($this->field);
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
        $permission = $this->model->where('id', $id)->first($this->field);
        return $permission ? $permission : $permission = null;
    }

    public function update($id, $data)
    {
        $permission = $this->model->find($id);
        if ($permission) {
            $permission->update($data);
            return $permission;
        }
        return null;
    }

    public function destroy($id)
    {
        $permission = $this->model->find($id);
        if ($permission) {
            $permission->delete();
            return $permission;
        }
        return null;
    }
}
