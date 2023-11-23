<?php
namespace App\Repositories\LogFinger;

Interface LogFingerRepositoryInterface{

    public function index($perPage, $search, $startDate, $endDate);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function logFingerUser($perPage, $startDate, $endDate);
}
