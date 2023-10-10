<?php
namespace App\Repositories\LeaveHistory;

Interface LeaveHistoryRepositoryInterface{

    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function deleteByLeaveId($id);
}
