<?php
namespace App\Repositories\SuratPeringatan;

Interface SuratPeringatanRepositoryInterface
{
    public function index($perPage, $search, $employeeId);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
