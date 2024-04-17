<?php

namespace App\Services\JobVacanciesApplied;

interface JobVacanciesAppliedServiceInterface
{
    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}