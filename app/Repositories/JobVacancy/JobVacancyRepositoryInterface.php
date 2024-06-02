<?php

namespace App\Repositories\JobVacancy;

interface JobVacancyRepositoryInterface
{
    public function index($perPage, $search, $startDate, $endDate, $status);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function indexPublic();
    public function applyJob(array $data);
    public function maritalStatus();
    public function religion();
    public function ethnic();
    public function relationship();
    public function education();
    public function job();
}
