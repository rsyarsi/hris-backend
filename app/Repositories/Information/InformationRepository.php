<?php

namespace App\Repositories\Information;

use App\Models\Information;
use Illuminate\Support\Facades\DB;
use App\Repositories\Information\InformationRepositoryInterface;
use Carbon\Carbon;

class InformationRepository implements InformationRepositoryInterface
{
    private $model;
    private $field = [
        'id',
        'employee_id',
        'legality_type_id',
        'started_at',
        'ended_at',
        'file_url',
        'file_path',
        'file_disk',
        'no_str'
    ];

    public function __construct(Information $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name', 'employment_number');
                        },
                        'legalityType' => function ($query) {
                            $query->select('id', 'name', 'active', 'extended');
                        },
                    ])
                    ->select($this->field);
        if ($search) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                                ->orWhere('employment_number', 'like', '%' . $search . '%');
                            });
            });
        }
        return $query->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $information = $this->model
                                    ->with([
                                        'employee' => function ($query) {
                                            $query->select('id', 'name', 'employment_number');
                                        },
                                        'legalityType' => function ($query) {
                                            $query->select('id', 'name', 'active', 'extended');
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

    public function employeeLegalitiesEnded($perPage, $search = null)
    {
        $query = $this->model
                    ->with([
                        'employee' => function ($query) {
                            $query->select('id', 'name', 'employment_number');
                        },
                        'legalityType' => function ($query) {
                            $query->select('id', 'name', 'active', 'extended');
                        },
                    ])
                    ->where('ended_at', '<=', now()->addMonths(3)->toDateString())
                    ->where(function ($subquery) use ($search) {
                        $lowerSearch = strtolower($search);
                        $subquery->orWhere('employee_id', $lowerSearch)
                                    ->orWhereHas('employee', function ($employeeQuery) use ($lowerSearch) {
                                        $employeeQuery->where(DB::raw('LOWER(name)'), 'like', '%' . $lowerSearch . '%');
                                    });
                    })
                    ->select($this->field);
        return $query->orderBy('ended_at', 'ASC')->paginate($perPage);
    }

    public function countEmployeeLegalitiesEnded()
    {
        $query = $this->model
                        ->where('ended_at', '<=', now()->addMonths(3)->toDateString())
                        ->count();
        return [
            'count' => $query
        ];
    }
}
