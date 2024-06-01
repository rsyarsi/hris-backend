<?php

namespace App\Services\Candidate;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\Candidate\CandidateServiceInterface;
use App\Repositories\Candidate\CandidateRepositoryInterface;

class CandidateService implements CandidateServiceInterface
{
    private $repository;

    public function __construct(CandidateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['first_name'] = $this->formatTextTitle($data['first_name']);
        $data['middle_name'] = $data['middle_name'] ?? null;
        $data['last_name'] = $data['last_name'] ?? null;
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['first_name'] = $this->formatTextTitle($data['first_name']);
        $data['middle_name'] = $data['middle_name'] ?? null;
        $data['last_name'] = $data['last_name'] ?? null;
        return $this->repository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function formatTextTitle($data)
    {
        return Str::title($data);
    }

    public function uploadCv($id, $data)
    {
        $file = $data['file'];
        if ($file && $file->isValid()) {
            // Upload the file to AWS S3 storage
            $filePath = $file->store('hrd/candidates/cv', 's3');
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
        return $this->repository->uploadCv($id, $data);
    }

    public function uploadPhotoCandidate($id, $data)
    {
        $file = $data['file'];
        if ($file && $file->isValid()) {
            // Upload the file to AWS S3 storage
            $filePath = $file->store('hrd/candidates/photo', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
        } else {
            $filePath = null;
            $fileUrl = null;
        }
        $data = [
            'photo_file_path' => $filePath,
            'photo_file_url' => $fileUrl,
            'photo_file_disk' => 's3',
        ];
        return $this->repository->uploadPhotoCandidate($id, $data);
    }
}
