<?php

namespace App\Repositories\SuratPeringatan;

use App\Models\SuratPeringatan;
use App\Repositories\SuratPeringatan\SuratPeringatanRepositoryInterface;

class SuratPeringatanRepository implements SuratPeringatanRepositoryInterface
{
    private $model;
    private $field =
    [
        'id',
        'user_created_id',
        'employee_id',
        'date',
        'no_surat',
        'type',
        'jenis_pelanggaran',
        'keterangan',
        'batal',
        'file_url',
        'file_path',
        'file_disk',
    ];


    public function __construct(SuratPeringatan $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null, $employeeId = null)
    {
        $query = $this->model
                        ->with([
                            'employee' => function ($query) {
                                $query->select('id', 'name', 'employment_number');
                            },
                            'userCreated' => function ($query) {
                                $query->select('id', 'name', 'email');
                            },
                        ])
                        ->select($this->field);
        if ($search !== null) {
            $query->where(function ($subquery) use ($search) {
                $subquery->orWhere('employee_id', $search)
                            ->orWhereHas('employee', function ($employeeQuery) use ($search) {
                                $employeeQuery->whereRaw('LOWER(name) LIKE ?', ["%".strtolower($search)."%"])
                                                ->orWhere('employment_number', 'like', '%' . $search . '%');
                            });
            });
        }
        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }
        return $query->orderBy('date', 'DESC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $suratperingatan = $this->model
                                ->with([
                                    'employee' => function ($query) {
                                        $query->select('id', 'name', 'employment_number');
                                    },
                                    'userCreated' => function ($query) {
                                        $query->select('id', 'name', 'email');
                                    },
                                ])
                                ->where('id', $id)
                                ->first($this->field);
        return $suratperingatan ? $suratperingatan : $suratperingatan = null;
    }

    public function update($id, $data)
    {
        $suratperingatan = $this->model->find($id);
        if ($suratperingatan) {
            $suratperingatan->update($data);
            return $suratperingatan;
        }
        return null;
    }

    public function destroy($id)
    {
        $suratperingatan = $this->model->find($id);
        if ($suratperingatan) {
            $suratperingatan->delete();
            return $suratperingatan;
        }
        return null;
    }
}
