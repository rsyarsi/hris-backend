<?php
namespace App\Services\User;

use Illuminate\Support\Str;
use App\Services\User\UserServiceInterface;
use App\Repositories\User\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['name'] = $this->formatTextTitle($data['name']);
        $data['password'] = $this->encryptPassword($data['password']);

        // Define the roles mapping
        $rolesMapping = [
            'ADMINISTRATOR' => 'administrator',
            'HRD' => 'hrd',
            'MANAGER' => 'manager',
            'SUPERVISOR' => 'supervisor',
            'EMPLOYEE' => 'pegawai', // Assuming you have a 'pegawai' role
            'KABAG' => 'kabag',
            'STAFF' => 'staff',
        ];

        // Loop through the roles and set the corresponding values in the $data array
        foreach ($data['role'] as $role) {
            if (array_key_exists($role, $rolesMapping)) {
                $data[$rolesMapping[$role]] = 1;
            }
        }
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['name'] = $this->formatTextTitle($data['name']);

        if(\array_key_exists('password', $data)) {
            $data['password'] = $this->encryptPassword($data['password']);
        }

        // Define the roles mapping
        $rolesMapping = [
            'ADMINISTRATOR' => 'administrator',
            'HRD' => 'hrd',
            'MANAGER' => 'manager',
            'SUPERVISOR' => 'supervisor',
            'EMPLOYEE' => 'pegawai', // Assuming you have a 'pegawai' role
            'KABAG' => 'kabag',
            'STAFF' => 'staff',
        ];

        // Initialize all fields to 0
        foreach ($rolesMapping as $field) {
            $data[$field] = 0;
        }

        // Loop through the roles and set the corresponding values in the $data array
        foreach ($data['role'] as $role) {
            if (\array_key_exists($role, $rolesMapping)) {
                $data[$rolesMapping[$role]] = 1;
            }
        }

        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function formatTextTitle($data)
    {
        return Str::upper($data);
    }

    public function encryptPassword($data)
    {
        return bcrypt($data);
    }

    public function updatePasswordMobile($data)
    {
        return $this->repository->updatePasswordMobile($data);
    }
}
