<?php

namespace App\Repositories\CoursesTrainingCandidate;

use App\Models\CoursesTrainingCandidate;
use App\Repositories\CoursesTrainingCandidate\CoursesTrainingCandidateRepositoryInterface;


class CoursesTrainingCandidateRepository implements CoursesTrainingCandidateRepositoryInterface
{
    private $model;

    public function __construct(CoursesTrainingCandidate $model)
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
                    ->orWhere('type_of_training', 'ILIKE', "%{$search}%")
                    ->orWhere('level', 'ILIKE', "%{$search}%")
                    ->orWhere('organized_by', 'ILIKE', "%{$search}%")
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
        $coursesTrainingCandidate = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
            ])
            ->where('id', $id)
            ->first();
        return $coursesTrainingCandidate ? $coursesTrainingCandidate : $coursesTrainingCandidate = null;
    }

    public function update($id, $data)
    {
        $coursesTrainingCandidate = $this->model->find($id);
        if ($coursesTrainingCandidate) {
            $coursesTrainingCandidate->update($data);
            return $coursesTrainingCandidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $coursesTrainingCandidate = $this->model->find($id);
        if ($coursesTrainingCandidate) {
            $coursesTrainingCandidate->delete();
            return $coursesTrainingCandidate;
        }
        return null;
    }
}
