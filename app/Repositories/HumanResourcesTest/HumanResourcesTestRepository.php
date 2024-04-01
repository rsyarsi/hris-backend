<?php

namespace App\Repositories\HumanResourcesTest;

use App\Models\HumanResourcesTest;
use App\Repositories\HumanResourcesTest\HumanResourcesTestRepositoryInterface;


class HumanResourcesTestRepository implements HumanResourcesTestRepositoryInterface
{
    private $model;

    public function __construct(HumanResourcesTest $model)
    {
        $this->model = $model;
    }

    public function index($perPage, $search = null)
    {
        $query = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
            ]);

        if ($search !== null) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('candidate_id', $search)
                    ->orWhereHas('candidate', function ($candidateQuery) use ($search) {
                        $candidateQuery->where('first_name', 'ILIKE', "%{$search}%")
                            ->orWhere('middle_name', 'ILIKE', "%{$search}%")
                            ->orWhere('last_name', 'ILIKE', "%{$search}%");
                    });
            });
        }
        return $query->orderBy('candidate_id', 'ASC')->paginate($perPage);
    }

    public function store(array $data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        $humanResourcesTest = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
            ])
            ->where('id', $id)
            ->first();
        return $humanResourcesTest ? $humanResourcesTest : $humanResourcesTest = null;
    }

    public function update($id, $data)
    {
        $humanResourcesTest = $this->model->find($id);
        if ($humanResourcesTest) {
            $humanResourcesTest->update($data);
            return $humanResourcesTest;
        }
        return null;
    }

    public function destroy($id)
    {
        $humanResourcesTest = $this->model->find($id);
        if ($humanResourcesTest) {
            $humanResourcesTest->delete();
            return $humanResourcesTest;
        }
        return null;
    }
}
