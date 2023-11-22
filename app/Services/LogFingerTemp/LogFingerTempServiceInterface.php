<?php
namespace App\Services\LogFingerTemp;

interface LogFingerTempServiceInterface
{
    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function logFingerUser($perPage, $search);
}
