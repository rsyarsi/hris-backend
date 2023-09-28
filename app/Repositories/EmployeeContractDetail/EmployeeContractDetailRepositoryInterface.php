<?php
namespace App\Repositories\EmployeeContractDetail;

Interface EmployeeContractDetailRepositoryInterface{

    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
