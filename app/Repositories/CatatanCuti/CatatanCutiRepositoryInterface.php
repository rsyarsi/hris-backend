<?php
namespace App\Repositories\CatatanCuti;

Interface CatatanCutiRepositoryInterface {

    public function index($perPage, $search);
    public function catatanCutiEmployee($perPage, $search, $employeeId);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
