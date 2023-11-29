<?php
namespace App\Services\Pph;

interface PphServiceInterface
{
    public function index($perPage, $search);
    public function pphEmployee($perPage, $search);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
}
