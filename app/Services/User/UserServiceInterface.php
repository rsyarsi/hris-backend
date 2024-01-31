<?php
namespace App\Services\User;

interface UserServiceInterface
{
    public function index($perPage, $search, $active);
    public function store(array $data);
    public function show($id);
    public function update($id, array $data);
    public function destroy($id);
    public function updatePasswordMobile(array $data);
    public function termConditionVerified(array $data);
}
