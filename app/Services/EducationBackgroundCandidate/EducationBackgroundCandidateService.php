<?php

namespace App\Services\EducationBackgroundCandidate;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\EducationBackgroundCandidate\EducationBackgroundCandidateServiceInterface;
use App\Repositories\EducationBackgroundCandidate\EducationBackgroundCandidateRepositoryInterface;

class EducationBackgroundCandidateService implements EducationBackgroundCandidateServiceInterface
{
    private $repository;

    public function __construct(EducationBackgroundCandidateRepositoryInterface $repository)
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
            $filePath = $file->store('hrd/candidate/education_backgrounds', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
        } else {
            $filePath = null;
            $fileUrl = null;
        }

        $data = [
            'candidate_id' => $data['candidate_id'],
            'education_id' => $data['education_id'],
            'institution_name' => $this->formatTextTitle($data['institution_name']),
            'major' => $this->formatTextTitle($data['major']),
            'started_year' => $data['started_year'],
            'ended_year' => $data['ended_year'],
            'final_score' => $data['final_score'],
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => 's3',
        ];

        return $this->repository->store($data);
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
            $filePath = $file->store('hrd/candidate/education_backgrounds', 's3');
            // Make the file public by setting ACL to 'public-read'
            Storage::disk('s3')->setVisibility($filePath, 'public');
            $fileUrl = Storage::disk('s3')->url($filePath);
        } else {
            $filePath = null;
            $fileUrl = null;
        }

        $finalData = [
            'candidate_id' => $data['candidate_id'],
            'education_id' => $data['education_id'],
            'institution_name' => $this->formatTextTitle($data['institution_name']),
            'major' => $this->formatTextTitle($data['major']),
            'started_year' => $data['started_year'],
            'ended_year' => $data['ended_year'],
            'final_score' => $data['final_score'],
            'file_path' => $filePath,
            'file_url' => $fileUrl,
            'file_disk' => 's3',
        ];
        return $this->repository->update($id, $finalData);
    }

    public function destroy($id)
    {
        return $this->repository->destroy($id);
    }

    public function indexByCandidate($candidateId)
    {
        return $this->repository->indexByCandidate($candidateId);
    }

    public function formatTextTitle($data)
    {
        return Str::upper($data);
    }
}
