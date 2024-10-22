<?php
namespace App\Services\Leave;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\Leave\LeaveServiceInterface;
use App\Repositories\Leave\LeaveRepositoryInterface;

class LeaveService implements LeaveServiceInterface
{
    private $repository;

    public function __construct(LeaveRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $period_1, $period_2, $unit)
    {
        return $this->repository->index($perPage, $search, $period_1, $period_2, $unit);
    }

    public function store(array $data)
    {
        $leaveType = $data['leave_type_id'];
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $durationInMinutes = $fromDate->diffInMinutes($toDate);
        $data['duration'] = $durationInMinutes;
        $data['leave_status_id'] = $data['leave_status_id'];
        $data['year'] = $fromDate->format('Y');
        if ($leaveType == 2) {
            $file = $data['file'];
            if ($file && $file->isValid()) {
                // Upload the file to AWS S3 storage
                $filePath = $file->store('hrd/leaves', 's3');
                // Make the file public by setting ACL to 'public-read'
                Storage::disk('s3')->setVisibility($filePath, 'public');
                $fileUrl = Storage::disk('s3')->url($filePath);
            } else {
                $filePath = null;
                $fileUrl = null;
            }
            $data['file_path'] = $filePath;
            $data['file_url'] = $fileUrl;
            $data['file_disk'] = 's3';
        }
        return $this->repository->store($data);
    }

    public function leaveCreateMobile(array $data)
    {
        $leaveType = $data['leave_type_id'];
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $durationInMinutes = $fromDate->diffInMinutes($toDate);
        $data['duration'] = $durationInMinutes;
        $data['leave_status_id'] = $data['leave_status_id'];
        $data['year'] = $fromDate->format('Y');
        if ($leaveType == 2) {
            $file = $data['file'];
            if ($file && $file->isValid()) {
                // Upload the file to AWS S3 storage
                $filePath = $file->store('hrd/leaves', 's3');
                // Make the file public by setting ACL to 'public-read'
                Storage::disk('s3')->setVisibility($filePath, 'public');
                $fileUrl = Storage::disk('s3')->url($filePath);
            } else {
                $filePath = null;
                $fileUrl = null;
            }
            $data['file_path'] = $filePath;
            $data['file_url'] = $fileUrl;
            $data['file_disk'] = 's3';
        }
        return $this->repository->leaveCreateMobile($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $leaveType = $data['leave_type_id'];
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
        $data['leave_status_id'] = $data['leave_status_id'];

        if ($leaveType == 2) {
            $file = $data['file'];
            if ($file && $file->isValid()) {
                // Upload the file to AWS S3 storage
                $filePath = $file->store('hrd/leaves', 's3');
                // Make the file public by setting ACL to 'public-read'
                Storage::disk('s3')->setVisibility($filePath, 'public');
                $fileUrl = Storage::disk('s3')->url($filePath);
            } else {
                $filePath = null;
                $fileUrl = null;
            }
            $data['file_path'] = $filePath;
            $data['file_url'] = $fileUrl;
            $data['file_disk'] = 's3';
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

    public function leaveHrdMobile()
    {
        return $this->repository->leaveHrdMobile();
    }

    public function leaveSupervisorOrManager($perPage, $search, $overtimeStatus, $startDate, $endDate)
    {
        $user = auth()->user();
        $allowedRoles = [
            'administrator',
            'hrd',
            'supervisor',
            'manager',
            'kabag',
            'ADMINISTRATOR',
            'HRD',
            'SUPERVISOR',
            'MANAGER',
            'KABAG',
        ];

        if ($user->hasAnyRole($allowedRoles)) {
            return $this->repository->leaveSupervisorOrManager($perPage, $search, $overtimeStatus, $startDate, $endDate);
        }
        return null;
    }

    public function leaveSupervisorOrManagerMobile($employeeId)
    {
        return $this->repository->leaveSupervisorOrManagerMobile($employeeId);
    }

    public function leaveStatus($perPage, $search, $period_1, $period_2, $leaveStatus, $unit)
    {
        $search = Str::upper($search);
        return $this->repository->leaveStatus($perPage, $search, $period_1, $period_2, $leaveStatus, $unit);
    }

    public function updateStatus($id, $data)
    {
        return $this->repository->updateStatus($id, $data);
    }

    public function updateStatusMobile($leaveId, $leaveStatusId)
    {
        return $this->repository->updateStatusMobile($leaveId, $leaveStatusId);
    }

    public function leaveSisa($employeeId)
    {
        return $this->repository->leaveSisa($employeeId);
    }
}
