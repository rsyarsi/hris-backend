<?php
namespace App\Services\CandidateAccount;

use Illuminate\Support\Str;
use App\Repositories\CandidateAccount\CandidateAccountRepositoryInterface;

class CandidateAccountService implements CandidateAccountServiceInterface
{
    private $repository;

    public function __construct(CandidateAccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $active)
    {
        return $this->repository->index($perPage, $search, $active);
    }

    public function store(array $data)
    {
        $data['name'] = $this->formatTextTitle($data['name']);
        $data['password'] = $this->encryptPassword($data['password']);
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

        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function formatTextTitle($data)
    {
        return Str::title($data);
    }

    public function encryptPassword($data)
    {
        return bcrypt($data);
    }
}
