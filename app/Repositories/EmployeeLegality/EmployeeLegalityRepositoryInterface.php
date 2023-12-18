<?php
namespace App\Repositories\EmployeeLegality;

Interface EmployeeLegalityRepositoryInterface{

    public function index($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function employeeLegalitiesEnded($perPage, $search);
    public function countEmployeeLegalitiesEnded();
}
