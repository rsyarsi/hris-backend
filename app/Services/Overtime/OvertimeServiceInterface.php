<?php
namespace App\Services\Overtime;

interface OvertimeServiceInterface
{
    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function overtimeStatus($perPage, $search, $overtimeStatus);
    public function updateStatus($id, $newStatusId);
}
