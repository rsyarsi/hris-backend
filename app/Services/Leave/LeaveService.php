<?php
namespace App\Services\Leave;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\Leave\LeaveServiceInterface;
use App\Services\Employee\EmployeeServiceInterface;
use App\Services\Firebase\FirebaseServiceInterface;
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
        $leaveType = $data['leave_type_id'];
        $fromDate = Carbon::parse($data['from_date']);
        $toDate = Carbon::parse($data['to_date']);
        $durationInMinutes = $fromDate->diffInMinutes($toDate);
        $data['duration'] = $durationInMinutes;
        $data['leave_status_id'] = $data['leave_status_id'];

        if ($leaveType == 2) {
            $file = $data['file'];
            if ($file && $file->isValid()) {
                // Upload the file to AWS S3 storage
                $filePath = $file->store('leaves', 's3');
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
                $filePath = $file->store('leaves', 's3');
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
