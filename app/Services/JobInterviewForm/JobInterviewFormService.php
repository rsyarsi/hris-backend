<?php
namespace App\Services\JobInterviewForm;

use Illuminate\Support\Str;
use App\Services\JobInterviewForm\JobInterviewFormServiceInterface;
use App\Repositories\JobInterviewForm\JobInterviewFormRepositoryInterface;

class JobInterviewFormService implements JobInterviewFormServiceInterface
{
    private $repository;

    public function __construct(JobInterviewFormRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search)
    {
        return $this->repository->index($perPage, $search);
    }

    public function store(array $data)
    {
        $data['status'] = $this->formatTextTitle($data['status']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['status'] = $this->formatTextTitle($data['status']);
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
}
