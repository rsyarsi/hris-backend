<?php
namespace App\Services\EmployeeLegality;

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
        $filePath = $this->handleFileUpload($data['file']);
        $legalityData = [
            'employee_id' => $data['employee_id'],
            'legality_type_id' => $data['legality_type_id'],
            'started_at' => $data['started_at'],
            'ended_at' => $data['ended_at'],
            'file_path' => $filePath,
            'file_url' => url('/storage') . '/' . $filePath,
            'file_disk' => 'public',
        ];
        return $this->repository->store($legalityData);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $filePath = $this->handleFileUpload($data['file']);
        $legalityData = [
            'employee_id' => $data['employee_id'],
            'legality_type_id' => $data['legality_type_id'],
            'started_at' => $data['started_at'],
            'ended_at' => $data['ended_at'],
            'file_path' => $filePath,
            'file_url' => url('/storage') . '/' . $filePath,
            'file_disk' => 'public',
        ];
        return $this->repository->update($id, $legalityData);
    }

    public function destroy($id)
    {
        // $data = $this->repository->show($id);
        // if ($data['file_path']) {
        //     $filePath = str_replace(url('storage/'), '', $data['file_path']);
        //     Storage::disk('public')->delete($filePath);
        // }
        return $this->repository->destroy($id);
    }

    public function handleFileUpload($file)
    {
        if ($file && $file->isValid()) {
            return $file->store('employee_legalities', 'public');
        }
        return null;
    }

}
