<?php

namespace App\Repositories\Candidate;

interface CandidateRepositoryInterface
{
    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function uploadCv($id, $data);
    public function destroy($id);
}
