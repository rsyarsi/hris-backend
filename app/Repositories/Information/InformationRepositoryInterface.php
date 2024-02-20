<?php
namespace App\Repositories\Information;

Interface InformationRepositoryInterface
{
    public function index($perPage, $search);
    public function indexMobile();
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
