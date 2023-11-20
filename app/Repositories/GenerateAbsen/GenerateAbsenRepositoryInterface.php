<?php
namespace App\Repositories\GenerateAbsen;

Interface GenerateAbsenRepositoryInterface{

    public function index($perPage, $search, $period_1, $period_2, $unit);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
