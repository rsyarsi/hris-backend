<?php
namespace App\Services\JobVacancy;

use Illuminate\Support\Str;
use App\Services\JobVacancy\JobVacancyServiceInterface;
use App\Repositories\JobVacancy\JobVacancyRepositoryInterface;

class JobVacancyService implements JobVacancyServiceInterface
{
    private $repository;

    public function __construct(JobVacancyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index($perPage, $search = null, $startDate = null, $endDate = null, $status = null)
    {
        return $this->repository->index($perPage, $search = null, $startDate = null, $endDate = null, $status = null);
    }

    public function store(array $data)
    {
        $data['user_created_id'] = auth()->id();
        $data['title'] = $this->formatTextTitle($data['title']);
        $data['position'] = $this->formatTextTitle($data['position']);
        return $this->repository->store($data);
    }

    public function show($id)
    {
        return $this->repository->show($id);
    }

    public function update($id, $data)
    {
        $data['user_created_id'] = auth()->id();
        $data['title'] = $this->formatTextTitle($data['title']);
        $data['position'] = $this->formatTextTitle($data['position']);
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
