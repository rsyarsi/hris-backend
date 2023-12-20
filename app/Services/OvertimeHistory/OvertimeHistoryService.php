<?php
namespace App\Services\OvertimeHistory;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\OvertimeHistory\OvertimeHistoryServiceInterface;
use App\Repositories\OvertimeHistory\OvertimeHistoryRepositoryInterface;

class OvertimeHistoryService implements OvertimeHistoryServiceInterface
{
    private $repository;

    public function __construct(OvertimeHistoryRepositoryInterface $repository)
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

    public function deleteByOvertimeId($id)
    {
        return $this->repository->deleteByOvertimeId($id);
    }
}
