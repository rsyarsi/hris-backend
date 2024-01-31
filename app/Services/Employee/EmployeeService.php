<?php
namespace App\Services\Employee;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\Employee\EmployeeServiceInterface;
use App\Repositories\Employee\EmployeeRepositoryInterface;

class EmployeeService implements EmployeeServiceInterface
{
    private $repository;

    public function __construct(EmployeeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search, $active)
    {
        return $this->repository->index($perPage, $search, $active);
    }

    public function store(array $data)
    {
        $data['name'] = $this->formatTextTitle($data['name']);
        $data['religion_id'] = 1;
        $data['resigned_at'] = '3000-01-01 00:00:00';
        return $this->repository->store($data);
    }

    public function employeeUploadPhoto($id, $data)
    {
        $file = $data['file'];
        if ($file && $file->isValid()) {
            // Upload the file to AWS S3 storage
            $filePath = $file->store('hrd/employees/photo', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
        } else {
            $filePath = null;
            $fileUrl = null;
        }
        $data = [
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => 's3',
        ];
        return $this->repository->employeeUploadPhoto($id, $data);
    }

    public function employeeUploadPhotoMobile($data)
    {
        $file = $data['file'];
        if ($file && $file->isValid()) {
            // Upload the file to AWS S3 storage
            $filePath = $file->store('hrd/employees/photo', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
        } else {
            $filePath = null;
            $fileUrl = null;
        }
        $data = [
            'employee_id' => $data['employee_id'],
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => 's3',
        ];
        return $this->repository->employeeUploadPhotoMobile($data);
    }

    public function employeeProfileMobile($employeeId)
    {
        return $this->repository->employeeProfileMobile($employeeId);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['name'] = $this->formatTextTitle($data['name']);
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function employeeNumberNull($perPage, $search)
    {
        return $this->repository->employeeNumberNull($perPage, $search);
    }

    public function employeeEndContract($perPage, $search)
    {
        return $this->repository->employeeEndContract($perPage, $search);
    }

    public function updateEmployeeContract($id, $data)
    {
        return $this->repository->updateEmployeeContract($id, $data);
    }

    public function updateUserId($id, $data)
    {
        return $this->repository->updateUserId($id, $data);
    }

    public function updateUnitId($id, $data)
    {
        return $this->repository->updateUnitId($id, $data);
    }

    public function updatePositionId($id, $data)
    {
        return $this->repository->updatePositionId($id, $data);
    }

    public function formatTextTitle($data)
    {
        return Str::title($data);
    }

    public function employeeWherePin($pin)
    {
        return $this->repository->employeeWherePin($pin);
    }

    public function employeeWhereEmployeeNumber($employmentNumber)
    {
        return $this->repository->employeeWhereEmployeeNumber($employmentNumber);
    }

    public function employeeActive($perPage, $search)
    {
        return $this->repository->employeeActive($perPage, $search);
    }

    public function employeeSubordinate($perPage, $search)
    {
        return $this->repository->employeeSubordinate($perPage, $search);
    }

    public function employeeSubordinateMobile($employeeId)
    {
        return $this->repository->employeeSubordinateMobile($employeeId);
    }

    public function employeeNonShift()
    {
        return $this->repository->employeeNonShift();
    }

    public function employeeHaveContractDetail()
    {
        return $this->repository->employeeHaveContractDetail();
    }

    public function employeeResigned($perPage, $search)
    {
        return $this->repository->employeeResigned($perPage, $search);
    }

}
