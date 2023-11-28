<?php
namespace App\Services\Leave;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\Leave\LeaveServiceInterface;
use App\Repositories\Leave\LeaveRepositoryInterface;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\Firebase\FirebaseServiceInterface;

class LeaveService implements LeaveServiceInterface
{
    private $repository;
    private $firebaseService;
    private $employeeService;

    public function __construct(LeaveRepositoryInterface $repository, FirebaseServiceInterface $firebaseService, EmployeeServiceInterface $employeeService)
    {
        $this->repository = $repository;
        $this->firebaseService = $firebaseService;
        $this->employeeService = $employeeService;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $durationInMinutes = $fromDate->diffInMinutes($toDate);
        $data['duration'] = $durationInMinutes;

        // $firebaseId = $data['duration'];
        // $typeSend = 'LEAVE';

        // $employeeId = $data['employee_id'];
        // $employee = $this->employeeService->show($employeeId);

        // $this->firebaseService->sendNotification($firebaseId, $typeSend, $employee);
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

    public function leaveEmployee($perPage, $overtimeStatus, $startDate, $endDate)
    {
        return $this->repository->leaveEmployee($perPage, $overtimeStatus, $startDate, $endDate);
    }

    public function leaveEmployeeMobile($employeeId)
    {
        return $this->repository->leaveEmployeeMobile($employeeId);
    }

    public function leaveSupervisorOrManager($perPage, $overtimeStatus, $startDate, $endDate)
    {
        $user = auth()->user();
        $allowedRoles = [
            'administrator',
            'hrd',
            'supervisor',
            'manager',
            'ADMINISTRATOR',
            'HRD',
            'SUPERVISOR',
            'MANAGER',
        ];

        if ($user->hasAnyRole($allowedRoles)) {
            return $this->repository->leaveSupervisorOrManager($perPage, $overtimeStatus, $startDate, $endDate);
        }
        return null;
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
