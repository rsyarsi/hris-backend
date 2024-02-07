<?php

namespace App\Repositories\Information;

use App\Models\Information;
use App\Repositories\Information\InformationRepositoryInterface;

class InformationRepository implements InformationRepositoryInterface
{
    private $model;

    public function __construct(Information $model)
    {
        $this->model = $model;
    }

    private $field = [
        'id',
        'name',
        'note',
        'user_id',
        'file_url',
        'file_path',
        'file_disk',
    ];
    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'user' => function ($query) {
                            $query->select('id', 'name', 'email');
                        },
                    ])
                    ->select($this->field);
        if ($search) {
            $query->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"]);
        }
        return $query->orderBy('created_at', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $information = $this->model
                            ->with([
                                'user' => function ($query) {
                                    $query->select('id', 'name', 'email');
                                },
                            ])
                            ->where('id', $id)
                            ->first($this->field);
        return $information ? $information : $information = null;
    }

    public function update($id, $data)
    {
        $information = $this->model->find($id);
        if ($information) {
            $information->update($data);
            return $information;
        }
        return null;
    }

    public function destroy($id)
    {
        $information = $this->model->find($id);
        if ($information) {
            $information->delete();
            return $information;
        }
        return null;
    }
}
