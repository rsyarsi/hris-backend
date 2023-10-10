<?php
namespace App\Services\Leave;

interface LeaveServiceInterface
{
    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function leaveStatus($perPage, $search, $leaveStatus);
    public function updateStatus($id, array $data);
}
