<?php
namespace App\Services\JobVacancy;

interface JobVacancyServiceInterface
{
    public function index($perPage, $search = null, $startDate = null, $endDate = null, $status = null);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
