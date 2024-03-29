<?php

namespace App\Services\ForeignLanguageCandidate;

use Illuminate\Support\Str;
use App\Services\ForeignLanguageCandidate\ForeignLanguageCandidateServiceInterface;
use App\Repositories\ForeignLanguageCandidate\ForeignLanguageCandidateRepositoryInterface;

class ForeignLanguageCandidateService implements ForeignLanguageCandidateServiceInterface
{
    private $repository;

    public function __construct(ForeignLanguageCandidateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['language'] = $this->formatTextTitle($data['language']);
        $data['speaking_ability_level'] = $this->formatTextTitle($data['speaking_ability_level']);
        $data['writing_ability_level'] = $this->formatTextTitle($data['writing_ability_level']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['language'] = $this->formatTextTitle($data['language']);
        $data['speaking_ability_level'] = $this->formatTextTitle($data['speaking_ability_level']);
        $data['writing_ability_level'] = $this->formatTextTitle($data['writing_ability_level']);
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function formatTextTitle($data)
    {
        return Str::upper($data);
    }
}
