<?php
namespace App\Services\Overtime;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\Overtime\OvertimeServiceInterface;
use App\Repositories\Overtime\OvertimeRepositoryInterface;

class OvertimeService implements OvertimeServiceInterface
{
    private $repository;

    public function __construct(OvertimeRepositoryInterface $repository)
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
        $durationInHours = $fromDate->floatDiffInHours($toDate);
        $roundedDuration = round($durationInHours, 2);
        $hours = floor($roundedDuration);
        $minutes = ($roundedDuration - $hours) * 60;
        $data['duration'] = sprintf('%02d.%02d', $hours, $minutes);
        return $this->repository->store($data);
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

    public function overtimeEmployee($perPage, $overtimeStatus)
    {
        return $this->repository->overtimeEmployee($perPage, $overtimeStatus);
    }

    public function overtimeStatus($perPage, $search, $overtimeStatus)
    {
        $search = Str::upper($search);
        return $this->repository->overtimeStatus($perPage, $search, $overtimeStatus);
    }

    public function updateStatus($id, $data)
    {
        return $this->repository->updateStatus($id, $data);
    }
}
