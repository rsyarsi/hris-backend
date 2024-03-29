<?php

namespace App\Repositories\ForeignLanguageCandidate;

use App\Models\ForeignLanguageCandidate;
use App\Repositories\ForeignLanguageCandidate\ForeignLanguageCandidateRepositoryInterface;


class ForeignLanguageCandidateRepository implements ForeignLanguageCandidateRepositoryInterface
{
    private $model;

    public function __construct(ForeignLanguageCandidate $model)
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
                    ->orWhere('language', 'ILIKE', "%{$search}%")
                    ->orWhere('speaking_ability_level', 'ILIKE', "%{$search}%")
                    ->orWhere('writing_ability_level', 'ILIKE', "%{$search}%")
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
        $foreignLanguageCandidate = $this->model
            ->with([
                'candidate:id,first_name,middle_name,last_name,email',
            ])
            ->where('id', $id)
            ->first();
        return $foreignLanguageCandidate ? $foreignLanguageCandidate : $foreignLanguageCandidate = null;
    }

    public function update($id, $data)
    {
        $foreignLanguageCandidate = $this->model->find($id);
        if ($foreignLanguageCandidate) {
            $foreignLanguageCandidate->update($data);
            return $foreignLanguageCandidate;
        }
        return null;
    }

    public function destroy($id)
    {
        $foreignLanguageCandidate = $this->model->find($id);
        if ($foreignLanguageCandidate) {
            $foreignLanguageCandidate->delete();
            return $foreignLanguageCandidate;
        }
        return null;
    }
}
