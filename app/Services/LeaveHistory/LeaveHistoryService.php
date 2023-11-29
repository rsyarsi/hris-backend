<?php
namespace App\Services\LeaveHistory;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
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
        $file = $data['file'];
        if ($file && $file->isValid()) {
            // Upload the file to AWS S3 storage
            $filePath = $file->store('employee_certificates', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
            $fileDisk = 's3';
        } else {
            $filePath = null;
            $fileUrl = null;
            $fileDisk = null;
        }

        $data = [
            'employee_id' => $data['employee_id'],
            'name' => $data['name'],
            'institution_name' => $data['institution_name'],
            'started_at' => $data['started_at'],
            'ended_at' => $data['ended_at'],
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => $fileDisk,
            'verified_at' => $data['verified_at'],
            'verified_user_Id' => $data['verified_user_Id'],
            'is_extended' => $data['is_extended'],
        ];
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
