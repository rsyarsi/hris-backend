<?php
namespace App\Services\Leave;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\Leave\LeaveServiceInterface;
use App\Repositories\Leave\LeaveRepositoryInterface;

class LeaveService implements LeaveServiceInterface
{
    private $repository;

    public function __construct(LeaveRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);

        // Check if from_date and to_date are the same
        if ($fromDate->eq($toDate)) {
            $data['duration'] = 1;
        } else {
            $duration = $fromDate->diffInDays($toDate);
            $data['duration'] = $duration;
        }

        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        if (isset($data['from_date']) || isset($data['to_date'])) {
            $fromDate = Carbon::parse($data['from_date'] ?? $this->repository->show($id)->from_date);
            $toDate = Carbon::parse($data['to_date'] ?? $this->repository->show($id)->to_date);

            // Check if from_date and to_date are the same
            if ($fromDate->eq($toDate)) {
                $data['duration'] = 1;
            } else {
                $duration = $fromDate->diffInDays($toDate);
                $data['duration'] = $duration;
            }
        }

        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function leaveEmployee($perPage, $overtimeStatus)
    {
        return $this->repository->leaveEmployee($perPage, $overtimeStatus);
    }

    public function leaveStatus($perPage, $search, $leaveStatus)
    {
        $search = Str::upper($search);
        return $this->repository->leaveStatus($perPage, $search, $leaveStatus);
    }

    public function updateStatus($id, $data)
    {
        return $this->repository->updateStatus($id, $data);
    }
}
