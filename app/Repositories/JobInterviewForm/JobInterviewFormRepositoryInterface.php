<?php
namespace App\Repositories\JobInterviewForm;

Interface JobInterviewFormRepositoryInterface
{
    public function index($perPage, $search, $period_1, $period_2);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function interviewer($perPage, $search, $period_1, $period_2);
}
