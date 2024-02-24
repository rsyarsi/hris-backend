<?php
namespace App\Services\OrderOvertime;

use Carbon\Carbon;
use App\Services\OrderOvertime\OrderOvertimeServiceInterface;
use App\Repositories\OrderOvertime\OrderOvertimeRepositoryInterface;

class OrderOvertimeService implements OrderOvertimeServiceInterface
{
    private $repository;

    public function __construct(OrderOvertimeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $period_1, $period_2, $unit, $status)
    {
        return $this->repository->index($perPage, $search, $period_1, $period_2, $unit, $status);
    }

    public function indexSubOrdinate($perPage, $search, $period_1, $period_2, $unit, $status)
    {
        return $this->repository->indexSubOrdinate($perPage, $search, $period_1, $period_2, $unit, $status);
    }

    public function indexSubOrdinateMobile($employeeId)
    {
        return $this->repository->indexSubOrdinateMobile($employeeId);
    }

    public function store(array $data)
    {
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $durationInHours = $fromDate->floatDiffInHours($toDate);
        $roundedDuration = round($durationInHours, 2);
        $hours = floor($roundedDuration);
        $minutes = ($roundedDuration - $hours) * 60;
        $data['duration'] = sprintf('%02d.%02d', $hours, $minutes);
        $data['user_created_id'] = auth()->id();
        return $this->repository->store($data);
    }

    public function storeMobile(array $data)
    {
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $durationInHours = $fromDate->floatDiffInHours($toDate);
        $roundedDuration = round($durationInHours, 2);
        $hours = floor($roundedDuration);
        $minutes = ($roundedDuration - $hours) * 60;
        $data['duration'] = sprintf('%02d.%02d', $hours, $minutes);
        $data['user_created_id'] = auth()->id();
        return $this->repository->storeMobile($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $durationInHours = $fromDate->floatDiffInHours($toDate);
        $roundedDuration = round($durationInHours, 2);
        $hours = floor($roundedDuration);
        $minutes = ($roundedDuration - $hours) * 60;
        $data['duration'] = sprintf('%02d.%02d', $hours, $minutes);
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function updateStatus($id, $data)
    {
        return $this->repository->updateStatus($id, $data);
    }

    public function updateStatusMobile($overtimeId, $status)
    {
        return $this->repository->updateStatusMobile($overtimeId, $status);
    }
}
