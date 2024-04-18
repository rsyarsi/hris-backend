<?php

namespace App\Services\LogGenerateAbsen;

interface LogGenerateAbsenServiceInterface
{
    public function index($perPage, $search, $period_1, $period_2, $unit);
    public function store(array $data);
    public function show($id);
    public function findDate($date, $employeeId);
}
