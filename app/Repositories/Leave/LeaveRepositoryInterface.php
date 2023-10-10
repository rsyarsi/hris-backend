<?php
namespace App\Repositories\Leave;

Interface LeaveRepositoryInterface{

    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function leaveStatus($perPage, $search, $overtimeStatus);
    public function updateStatus($id, array $data);
}
