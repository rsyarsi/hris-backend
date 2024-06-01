<?php

namespace App\Services\Candidate;

interface CandidateServiceInterface
{
    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function uploadCv($id, $data);
    public function uploadPhotoCandidate($id, $data);
    public function destroy($id);
}
