<?php
namespace App\Services\LogFinger;

use App\Services\LogFinger\LogFingerServiceInterface;
use App\Repositories\LogFinger\LogFingerRepositoryInterface;

class LogFingerService implements LogFingerServiceInterface
{
    private $repository;

    public function __construct(LogFingerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $startDate, $endDate)
    {
        return $this->repository->index($perPage, $search, $startDate, $endDate);
    }

    public function store(array $data)
    {
        $data['user_manual_id'] = auth()->id();
        $data['input_manual_at'] = now();
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

    public function logFingerUser($perPage, $startDate, $endDate)
    {
        return $this->repository->logFingerUser($perPage, $startDate, $endDate);
    }
}
