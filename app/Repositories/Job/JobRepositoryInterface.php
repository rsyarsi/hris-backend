<?php
namespace App\Repositories\Job;

Interface JobRepositoryInterface{

    public function index();
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
