<?php
namespace App\Services\EmployeeContract;

interface EmployeeContractServiceInterface
{
    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function getLastTransactionNumber();
    public function generateNextTransactionNumber();
}
