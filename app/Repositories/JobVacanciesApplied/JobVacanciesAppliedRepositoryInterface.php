<?php

namespace App\Repositories\JobVacanciesApplied;

interface JobVacanciesAppliedRepositoryInterface
{
    public function index($perPage, $search, $status);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
