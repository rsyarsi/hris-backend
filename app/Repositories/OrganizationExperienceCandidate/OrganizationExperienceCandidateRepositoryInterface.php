<?php

namespace App\Repositories\OrganizationExperienceCandidate;

interface OrganizationExperienceCandidateRepositoryInterface
{
    public function index($perPage, $search);
    public function indexByCandidate($candidateId);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
