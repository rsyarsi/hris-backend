<?php
namespace App\Services\Deduction;

interface DeductionServiceInterface
{
    public function index($perPage, $search, $period);
    public function deductionEmployee($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
