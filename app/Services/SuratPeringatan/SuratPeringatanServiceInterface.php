<?php
namespace App\Services\SuratPeringatan;

interface SuratPeringatanServiceInterface
{
    public function index($perPage, $search, $employeeId);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
