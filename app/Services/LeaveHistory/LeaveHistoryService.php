<?php
namespace App\Services\LeaveHistory;

use Illuminate\Support\Str;
use App\Services\LeaveHistory\LeaveHistoryServiceInterface;
use App\Repositories\LeaveHistory\LeaveHistoryRepositoryInterface;

class LeaveHistoryService implements LeaveHistoryServiceInterface
{
    private $repository;

    public function __construct(LeaveHistoryRepositoryInterface $repository)
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

    public function formatTextTitle($data)
    {
        return Str::upper($data);
    }

    public function deleteByLeaveId($id)
    {
        return $this->repository->deleteByLeaveId($id);
    }
}
