<?php
namespace App\Services\LeaveApproval;

use Illuminate\Support\Str;
use App\Services\LeaveApproval\LeaveApprovalServiceInterface;
use App\Repositories\LeaveApproval\LeaveApprovalRepositoryInterface;

class LeaveApprovalService implements LeaveApprovalServiceInterface
{
    private $repository;

    public function __construct(LeaveApprovalRepositoryInterface $repository)
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
}
