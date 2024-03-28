<?php

namespace App\Repositories\Candidate;

use App\Models\{Candidate};
use App\Repositories\Candidate\CandidateRepositoryInterface;


class CandidateRepository implements CandidateRepositoryInterface
{
    private $model;

    public function __construct(Candidate $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
                        ->with([
                            'identityType:id,name',
                            'sex:id,name',
                            'maritalStatus:id,name',
                            'religion:id,name',
                            'ethnic:id,name',
                            'candidateAccount:id,name,email,username,active',
                        ]);

        if ($search !== null) {
            $query->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(first_name) LIKE ?', ["%".strtolower($search)."%"])
                    ->orWhere('middle_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        return $query->orderBy('first_name', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $candidate = $this->model
                            ->with([
                                'identityType:id,name',
                                'sex:id,name',
                                'maritalStatus:id,name',
                                'religion:id,name',
                                'ethnic:id,name',
                                'candidateAccount:id,name,email,username,active',
                            ])
                            ->where('id', $id)
                            ->first();
        return $candidate ? $candidate : $candidate = null;
    }

    public function update($id, $data)
    {
        $candidate = $this->model->find($id);
        if ($candidate) {
            $candidate->update($data);
            return $candidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $candidate = $this->model->find($id);
        if ($candidate) {
            $candidate->delete();
            return $candidate;
        }
        return null;
    }
}