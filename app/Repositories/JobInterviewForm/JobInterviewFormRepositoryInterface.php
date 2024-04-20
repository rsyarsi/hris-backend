<?php

namespace App\Repositories\JobInterviewForm;

interface JobInterviewFormRepositoryInterface
{
    public function index($perPage, $search, $period_1, $period_2, $status);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function interviewer($perPage, $search, $period_1, $period_2, $status);
}
