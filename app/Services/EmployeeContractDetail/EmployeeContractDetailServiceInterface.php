<?php
namespace App\Services\EmployeeContractDetail;

interface EmployeeContractDetailServiceInterface
{
    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function deleteByEmployeeContractId($id);
    public function storeMultiple(array $data);
}
