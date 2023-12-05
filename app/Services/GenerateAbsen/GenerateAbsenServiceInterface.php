<?php
namespace App\Services\GenerateAbsen;

interface GenerateAbsenServiceInterface
{
    public function index($perPage, $search, $period_1, $period_2, $unit);
    public function store(array $data);
    public function show($id);
    public function findDate($date, $employeeId);
    public function update($id, array $data);
    public function destroy($id);
    public function absenFromMobile(array $data);
}
