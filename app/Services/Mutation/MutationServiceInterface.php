<?php
namespace App\Services\Mutation;

interface MutationServiceInterface
{
    public function index($perPage, $search);
    public function mutationEmployee($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
