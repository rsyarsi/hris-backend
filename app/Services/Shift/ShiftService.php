<?php
namespace App\Services\Shift;

use Illuminate\Support\Str;
use App\Services\Shift\ShiftServiceInterface;
use App\Repositories\Shift\ShiftRepositoryInterface;

class ShiftService implements ShiftServiceInterface
{
    private $repository;

    public function __construct(ShiftRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $groupShiftId, $active)
    {
        return $this->repository->index($perPage, $search, $groupShiftId, $active);
    }

    public function store(array $data)
    {
        $data['name'] = $this->formatTextTitle($data['name']);
        $data['code'] = $this->formatTextTitle($data['code']);
        $data['user_created_id'] = auth()->id();
        $data['user_updated_id'] = auth()->id();
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['name'] = $this->formatTextTitle($data['name']);
        $data['code'] = $this->formatTextTitle($data['code']);
        $data['user_updated_id'] = auth()->id();
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

    public function searchShiftLibur($shiftGroupId)
    {
        return $this->repository->searchShiftLibur($shiftGroupId);
    }
}
