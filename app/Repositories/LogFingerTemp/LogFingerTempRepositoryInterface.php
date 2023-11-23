<?php
namespace App\Repositories\LogFingerTemp;

Interface LogFingerTempRepositoryInterface{

    public function index($perPage, $search, $startDate, $endDate);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function logFingerTempUser($perPage, $startDate, $endDate);
}
