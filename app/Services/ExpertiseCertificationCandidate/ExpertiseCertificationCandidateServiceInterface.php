<?php

namespace App\Services\ExpertiseCertificationCandidate;

interface ExpertiseCertificationCandidateServiceInterface
{
    public function index($perPage, $search);
    public function indexByCandidate($candidateId);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
