<?php
namespace App\Services\ShiftScheduleExchange;

interface ShiftScheduleExchangeServiceInterface
{
    public function index($perPage, $search, $startDate, $endDate);
    public function store(array $data);
    public function createMobile(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
