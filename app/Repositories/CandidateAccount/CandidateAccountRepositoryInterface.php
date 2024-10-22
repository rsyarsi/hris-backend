<?php
namespace App\Repositories\CandidateAccount;

Interface CandidateAccountRepositoryInterface
{
    public function index($perPage, $search, $active);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
