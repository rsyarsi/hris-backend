<?php
namespace App\Services\EmployeeLegality;

use Illuminate\Support\Facades\Storage;
use App\Services\EmployeeLegality\EmployeeLegalityServiceInterface;
use App\Repositories\EmployeeLegality\EmployeeLegalityRepositoryInterface;

class EmployeeLegalityService implements EmployeeLegalityServiceInterface
{
    private $repository;

    public function __construct(EmployeeLegalityRepositoryInterface $repository)
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
            $filePath = $file->store('employee_legalities', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
        } else {
            $filePath = null;
            $fileUrl = null;
        }

        $legalityData = [
            'employee_id' => $data['employee_id'],
            'legality_type_id' => $data['legality_type_id'],
            'started_at' => $data['started_at'],
            'ended_at' => $data['ended_at'],
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => 's3',
        ];
        return $this->repository->store($legalityData);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $file = $data['file'];
        if ($file && $file->isValid()) {
            // Upload the file to AWS S3 storage
            $filePath = $file->store('employee_legalities', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
        } else {
            $filePath = null;
            $fileUrl = null;
        }
        $legalityData = [
            'employee_id' => $data['employee_id'],
            'legality_type_id' => $data['legality_type_id'],
            'started_at' => $data['started_at'],
            'ended_at' => $data['ended_at'],
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => 's3',
        ];
        return $this->repository->update($id, $legalityData);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }
}
