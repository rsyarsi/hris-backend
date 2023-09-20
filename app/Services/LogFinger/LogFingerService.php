<?php
namespace App\Services\LogFinger;

use Illuminate\Support\Str;
use App\Services\LogFinger\LogFingerServiceInterface;
use App\Repositories\LogFinger\LogFingerRepositoryInterface;

class LogFingerService implements LogFingerServiceInterface
{
    private $repository;

    public function __construct(LogFingerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}
