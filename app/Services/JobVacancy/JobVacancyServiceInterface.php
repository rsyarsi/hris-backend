<?php
namespace App\Services\JobVacancy;

interface JobVacancyServiceInterface
{
    public function index($perPage, $search, $startDate, $endDate, $status);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function indexPublic();
}
